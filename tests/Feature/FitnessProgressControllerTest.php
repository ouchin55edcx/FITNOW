<?php

namespace Tests\Feature;

use App\Models\PhysicalProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhysicalProgressControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreProgress()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'weight' => 75.5,
            'measurements' => 180.5,
            'sports_performance' => 'Good',
        ];

        $response = $this->postJson('/api/progress', $data);

        $response->assertStatus(201)
            ->assertJson([
                'weight' => 75.5,
                'measurements' => 180.5,
                'sports_performance' => 'Good',
                'user_id' => $user->id,
            ]);
    }

    public function testGetProgressHistory()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $progress1 = PhysicalProgress::factory()->create(['user_id' => $user->id]);
        $progress2 = PhysicalProgress::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/progress');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $progress1->id,
                'user_id' => $user->id,
                'weight' => $progress1->weight,
                'measurements' => $progress1->measurements,
                'sports_performance' => $progress1->sports_performance,
            ])
            ->assertJsonFragment([
                'id' => $progress2->id,
                'user_id' => $user->id,
                'weight' => $progress2->weight,
                'measurements' => $progress2->measurements,
                'sports_performance' => $progress2->sports_performance,
            ]);
    }

    public function testDeleteProgress()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $progress = PhysicalProgress::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/progress/{$progress->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Physical progress deleted successfully.',
            ]);

        $this->assertDatabaseMissing('physical_progress', [
            'id' => $progress->id,
            'user_id' => $user->id,
        ]);
    }

    public function testUpdateProgress()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $progress = PhysicalProgress::factory()->create(['user_id' => $user->id]);

        $data = [
            'weight' => 80.0,
            'measurements' => 182.0,
            'sports_performance' => 'Excellent',
        ];

        $response = $this->putJson("/api/progress/{$progress->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $progress->id,
                'user_id' => $user->id,
                'weight' => 80.0,
                'measurements' => 182.0,
                'sports_performance' => 'Excellent',
            ]);
    }

    public function testUpdateProgressStatus()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $progress = PhysicalProgress::factory()->create(['user_id' => $user->id]);

        $data = [
            'status' => 'Completed',
        ];

        $response = $this->patchJson("/api/progress/{$progress->id}/status", $data);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $progress->id,
                'user_id' => $user->id,
                'status' => 'Completed',
            ]);
    }
}