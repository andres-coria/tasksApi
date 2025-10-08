<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->token = $this->user->createToken('api_token')->plainTextToken;
    }

    /** @test */
    public function cannot_access_tasks_without_token()
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401)
                 ->assertJson([
                    'message' => 'Unauthenticated.'
                 ]);
    }

    /** @test */
    public function can_create_task_with_token()
    {
        $payload = [
            'title' => 'New Task',
            'description' => 'Test description',
            'status' => 'PENDING',
        ];

        $response = $this->postJson('/api/tasks', $payload, [
            'Authorization' => 'Bearer '.$this->token,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                    'task' =>['id', 'title', 'description', 'status', 'created_at', 'updated_at'
                 ]]);

        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    /** @test */
    public function can_list_tasks_with_token()
    {
        Task::factory()->create([
            'title' => 'Task 1',
            'description' => 'Desc 1',
            'status' => 'DONE'
        ]);

        $response = $this->getJson('/api/tasks', [
            'Authorization' => 'Bearer '.$this->token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['tasks']);
    }

    /** @test */
    public function can_update_task_with_token()
    {
        $task = Task::create([
            'title' => 'Old task',
            'description' => 'Desc',
            'status' => 'PENDING'
        ]);

        $payload = [
            'title' => 'Task Updated',
            'description' => 'Another Desc',
            'status' => 'IN_PROGRESS',
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload, [
            'Authorization' => 'Bearer '.$this->token,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Task Updated']);

        $this->assertDatabaseHas('tasks', ['title' => 'Task Updated']);
    }

    /** @test */
    public function can_delete_task_with_token()
    {
        $task = Task::create([
            'title' => 'To Be deleted',
            'description' => 'Desc',
            'status' => 'PENDING'
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}", [], [
            'Authorization' => 'Bearer '.$this->token,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Task removed']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
