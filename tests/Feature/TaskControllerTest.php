<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_tasks_ordered_by_status(): void
    {
        Task::factory()->create(['status' => TaskStatus::COMPLETED->name]);
        Task::factory()->create(['status' => TaskStatus::PENDING->name]);

        $response = $this->getJson(route('tasks.index'));

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(2, 'data');

        // assert first returned task is the one with pending status
        $task = $response->json('data.0');

        $this->assertEquals(TaskStatus::PENDING->name, $task['status']);
    }

    public function test_creates_a_task(): void
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'A test description',
            'status' => TaskStatus::PENDING->name,
        ];

        $response = $this->postJson(route('tasks.store'), $payload);

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json): \Illuminate\Testing\Fluent\AssertableJson =>
                $json->where('data.title', $payload['title'])
                    ->where('data.description', $payload['description'])
                    ->where('data.status', $payload['status'])
                    ->etc()
            );

        $this->assertDatabaseHas('tasks', $payload);
    }

    public function test_validates_task_creation(): void
    {
        $response = $this->postJson(route('tasks.store'), [
            'title' => str_repeat('A', 256), // Too long
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title']);
    }

    public function test_updates_a_task(): void
    {
        $task = Task::factory()->create([
            'title' => 'Old Title',
            'status' => TaskStatus::PENDING->name,
        ]);

        $payload = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'status' => TaskStatus::COMPLETED->name,
        ];

        $response = $this->putJson(route('tasks.update', $task), $payload);

        $response->assertOk()
            ->assertJsonPath('data.title', $payload['title']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $payload['title'],
            'description' => $payload['description'],
            'status' => TaskStatus::COMPLETED->name,
        ]);
    }

    public function test_validates_task_update(): void
    {
        $task = Task::factory()->create([
            'title' => 'Old Title',
            'status' => TaskStatus::PENDING->name,
        ]);

        $response = $this->putJson(route('tasks.update', ['task' => $task]), [
            'title' => Str::random(256), // too long
            'status' => 'SOME STATUS', // not existing
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'status']);
    }

    public function test_deletes_a_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson(route('tasks.destroy', $task));

        $response->assertNoContent();

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
