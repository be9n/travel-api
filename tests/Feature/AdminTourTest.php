<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Travel;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTourTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_user_cannot_access_adding_tour(): void
    {
        $travel = Travel::factory()->create();
        $response = $this->postJson('/api/admin/travels/' . $travel->slug . '/tours');

        $response->assertStatus(401);
    }

    public function test_non_admin_user_cannot_access_adding_tour(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'editor')->value('id'));

        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/admin/travels/' . $travel->slug . '/tours');

        $response->assertStatus(403);
    }

    public function test_saves_tour_successfully_with_valid_data(): void
    {
        $this->seed(RoleSeeder::class);
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->value('id'));

        $travel = Travel::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/admin/travels/' . $travel->slug . '/tours', [
            'name' => 'New Travel',
        ]);

        $response->assertStatus(422);

        $response = $this->actingAs($user)->postJson('/api/admin/travels/' . $travel->slug . '/tours', [
            'name' => 'New Travel',
            'starting_date' => now(),
            'ending_date' => now()->addDay(),
            'price' => 2500,
        ]);

        $response->assertStatus(201);

        $response = $this->get('/api/travel/' . $travel->slug . '/tours');
        $response->assertJsonFragment(['name' => 'New Travel']);
    }
}
