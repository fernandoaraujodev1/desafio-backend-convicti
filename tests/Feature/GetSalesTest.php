<?php

namespace Tests\Feature;

use App\Models\Directorship;
use App\Models\Sale;
use App\Models\Unity;
use App\Models\User;
use App\Models\UserDirectorship;
use App\Models\UserUnities;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GetSalesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    private function createUserWithRole(string $roleName)
    {
        $user = User::factory()->create();
        $role = Role::findByName($roleName);
        $user->assignRole($role);

        return $user;
    }

    private function authenticateUserWithRole(string $roleName)
    {
        $user = $this->createUserWithRole($roleName);
        $this->actingAs($user, 'web');

        return $user;
    }

    public function testGeneralDirectorCanFilterSales()
    {
        $this->authenticateUserWithRole('general-director');

        $directorship = Directorship::factory()->create();
        $unity = Unity::factory()->create(['directorship_id' => $directorship->id]);
        $salesman = User::factory()->create();
        $salesman->assignRole('salesman');
        $salesman->unities()->attach($unity->id);

        $sale = Sale::factory()->create(['user_id' => $salesman->id]);

        $response = $this->json('GET', '/api/get-sales', [
            'directorship_id' => $directorship->id,
            'unity_id' => $unity->id,
            'seller_id' => $salesman->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $sale->id]);
    }

    public function testDirectorCanFilterSalesByDirectoryUnityAndSeller()
    {
        $director = $this->authenticateUserWithRole('director');
        $directorship = Directorship::factory()->create();
        $unity = Unity::factory()->create(['directorship_id' => $directorship->id]);

        $salesman = User::factory()->create();
        $salesman->assignRole('salesman');
        $salesman->unities()->attach($unity->id);

        $sale = Sale::factory()->create(['user_id' => $salesman->id]);

        UserDirectorship::factory()->create([
            'user_id' => $director->id,
            'directorship_id' => $directorship->id,
        ]);

        $response = $this->json('GET', '/api/get-sales', [
            'directorship_id' => $directorship->id,
            'unity_id' => $unity->id,
            'seller_id' => $salesman->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data'])
            ->assertJsonFragment(['id' => $sale->id]);
    }

    public function testManagerCanFilterSalesByUnity()
    {
        $user = $this->authenticateUserWithRole('manager');

        $unity = Unity::factory()->create();
        $salesman = User::factory()->create();
        $salesman->assignRole('salesman');
        $salesman->unities()->attach($unity->id);

        $user->unities()->attach($unity->id);

        UserUnities::factory()->create([
            'user_id' => $user->id,
            'unity_id' => $unity->id,
        ]);

        $sale = Sale::factory()->create(['user_id' => $salesman->id]);

        $response = $this->json('GET', '/api/get-sales', [
            'unity_id' => $unity->id,
            'seller_id' => $salesman->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $sale->id]);
    }

    public function testDirectorCannotFilterSalesWithoutPermission()
    {
        $this->authenticateUserWithRole('director');

        $directorship = Directorship::factory()->create();
        $unity = Unity::factory()->create(['directorship_id' => $directorship->id]);
        $salesman = User::factory()->create();
        $salesman->assignRole('salesman');
        $salesman->unities()->attach($unity->id);

        Sale::factory()->create(['user_id' => $salesman->id]);

        $response = $this->json('GET', '/api/get-sales', [
            'directorship_id' => $directorship->id,
            'unity_id' => $unity->id,
            'seller_id' => $salesman->id,
        ]);

        $response->assertStatus(409);
    }

    public function testSalesmanCannotUseWebEndpoint()
    {
        $user = $this->authenticateUserWithRole('salesman');

        $token = $user->createToken('test-token', ['app-access'])->plainTextToken;

        Sanctum::actingAs(
            $user,
            ['app-access']
        );

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->json('GET', '/api/get-sales');

        $response->assertStatus(401);
    }

    public function testDirectorCanFilterSalesByDirectorshipAndUnityWithDateFilters()
    {
        $director = $this->authenticateUserWithRole('director');

        $directorship = Directorship::factory()->create();
        $unity = Unity::factory()->create(['directorship_id' => $directorship->id]);

        $salesman = User::factory()->create();
        $salesman->assignRole('salesman');
        $salesman->unities()->attach($unity->id);

        UserDirectorship::factory()->create([
            'user_id' => $director->id,
            'directorship_id' => $directorship->id,
        ]);

        $sale1 = Sale::factory()->create([
            'user_id' => $salesman->id,
            'value' => 100,
            'created_at' => now()->subDays(5),
        ]);

        $sale2 = Sale::factory()->create([
            'user_id' => $salesman->id,
            'value' => 200,
            'created_at' => now()->subDays(10),
        ]);

        $initialDate = now()->subDays(7)->format('Y-m-d H:i:s');
        $finalDate = now()->format('Y-m-d H:i:s');

        $response = $this->json('GET', '/api/get-sales', [
            'directorship_id' => $directorship->id,
            'unity_id' => $unity->id,
            'seller_id' => $salesman->id,
            'initial_date' => $initialDate,
            'final_date' => $finalDate,
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $sale1->id])
            ->assertJsonMissing(['id' => $sale2->id]);
    }
}
