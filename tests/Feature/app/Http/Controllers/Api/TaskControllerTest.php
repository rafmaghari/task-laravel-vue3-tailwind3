<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->make();
        $params = [
            'title' => $task->title,
            'description' => $task->description,
            'due_date' => $task->due_date,
            'priority' => random_int(0,3),
        ];
        $response = $this->postJson(route('tasks.store'), $params);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', ['title' => $task->title, 'description' => $task->description]);
    }

    public function test_can_update_task()
    {
        $auth = Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->create(['user_id' => $auth->id]);

        $params = [
            'title' => 'UPDATED',
            'description' => 'UPDATED',
            'due_date' => $task->due_date,
        ];
        $response = $this->putJson(route('tasks.update', $task->id), $params);

        $response->assertOk();
        $this->assertDatabaseHas('tasks', ['title' => 'UPDATED', 'description' => 'UPDATED']);
    }

    public function test_can_show_task()
    {
        $auth = Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->create(['user_id' => $auth->id]);

        $response = $this->getJson(route('tasks.show', $task->id));

        $response->assertOk();
    }

    public function test_can_delete_task()
    {
        $auth = Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->create(['user_id' => $auth->id]);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertStatus(204);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_can_update_status()
    {
        $auth = Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->create(['user_id' => $auth->id]);

        $response = $this->putJson(route('update.status', $task->id), ['status' => 2]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id'=> $task->id, 'status' => 2]);
    }

    public function test_can_update_due_date()
    {
        $auth = Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->create(['user_id' => $auth->id]);

        $now = now()->format('Y-m-d');
        $response = $this->putJson(route('update.due_date', $task->id), ['due_date' => $now]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['id'=> $task->id, 'due_date' => $now]);
    }

    public function test_can_update_archived()
    {
        $auth = Sanctum::actingAs(User::factory()->create(), ['*']);
        $task = Task::factory()->create(['user_id' => $auth->id]);

        $response = $this->putJson(route('update.archived', $task->id), ['status' => 'archived']);

        $response->assertStatus(200);
        $task = Task::where('id', $task->id)->whereNotNull('archived_at')->get();
        $this->assertEquals(1, $task->count());
    }
}
