<?php

namespace Tests\Feature;

use App\Models\Sale;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetSellerSalesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function testSalesFilterBySellerId()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $sale = Sale::factory()->create(['user_id' => $user->id]);

        $response = $this->json('GET', '/api/mobile/get-sales', [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $sale->id]);
    }

    public function testSalesFilterByDateRange()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $saleWithinRange = Sale::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(5),
        ]);

        $saleOutsideRange = Sale::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(10),
        ]);

        $response = $this->json('GET', '/api/mobile/get-sales', [
            'user_id' => $user->id,
            'initial_date' => now()->subDays(7)->format('Y-m-d H:i:s'),
            'final_date' => now()->subDays(3)->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $saleWithinRange->id])
            ->assertJsonMissing(['id' => $saleOutsideRange->id]);
    }
}
