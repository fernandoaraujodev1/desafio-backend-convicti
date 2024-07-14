<?php

namespace Tests\Feature;

use App\Jobs\FindClosestUnity;
use App\Models\User;
use App\Repositories\Sale\SaleRepository;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PlaceSaleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function testPlaceSaleSuccess()
    {
        Queue::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'value' => $this->faker->randomFloat(2, 1, 1000),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
        ];

        $response = $this->json('POST', '/api/mobile/place-sales', $data);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['success', 'data' => ['id', 'value', 'lat', 'long', 'user_id']]);

        $this->assertDatabaseHas('sales', [
            'user_id' => $user->id,
            'value' => $data['value'],
            'lat' => $data['lat'],
            'long' => $data['long'],
        ]);

        Queue::assertPushed(FindClosestUnity::class, function ($job) use ($data) {
            return $job->sale->value === $data['value'] &&
                $job->sale->lat === $data['lat'] &&
                $job->sale->long === $data['long'];
        });
    }

    public function testPlaceSaleFailure()
    {
        Queue::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'value' => null,
            'lat' => null,
            'long' => null,
        ];

        $response = $this->json('POST', '/api/mobile/place-sales', $data);

        $response->assertStatus(409)
            ->assertJson(['success' => false])
            ->assertJsonStructure(['success', 'message']);
    }

    public function testPlaceSaleException()
    {
        Queue::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->mock(SaleRepository::class, function ($mock) {
            $mock->shouldReceive('createSale')->andThrow(new \Exception('Test Exception'));
        });

        $data = [
            'value' => $this->faker->randomFloat(2, 1, 1000),
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
        ];

        $response = $this->json('POST', '/api/mobile/place-sales', $data);

        $response->assertStatus(500)
            ->assertJson(['success' => false])
            ->assertJsonStructure(['success', 'message']);

        Queue::assertNothingPushed();
    }
}
