<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends TestCase
{

    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uri = '/api/tasks';

    }

    /** @test */
    public function user_can_read_all_tasks()
    {
        $tasksCount = $this->faker->numberBetween(1, 10);

        Task::factory()->count($tasksCount)->create();

        $response = $this->getJson($this->uri);

        $response->assertStatus(200)
                 ->assertJsonCount($tasksCount);
    }

    /** @test */
    public function user_can_create_a_task()
    {
        $taskRequest = [
            'title' => 'Test Task 1',
            'description' => 'Test Description 1',
            'status' => 'pending'
        ];

        $response = $this->postJson($this->uri, $taskRequest);

        $response->assertStatus(201)
                 ->assertJsonFragment($taskRequest);

        $this->assertDatabaseHas('tasks', $taskRequest);
    }

     /** @test */
     public function users_can_read_specific_task_by_id()
     {
        $task = Task::factory()->create();


         $this->get($this->uri . '/' . $task->id)
             ->assertStatus(Response::HTTP_OK)
          ->assertJsonFragment([
                     'title' => $task->title,
                     'description' => $task->description,
                     'status' => $task->status,
                     'due_date' => $task->due_date,
                 ]);
     }


    /** @test */
    public function user_cannot_read_specific_task_by_id_if_does_not_exists()
    {
        $id = 99999;
        $this->get($this->uri . '/' . $id)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function user_can_update_a_task()
    {

        $task = Task::factory()->create();

        $requestBody = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'completed',
        ];


        $this->putJson($this->uri . '/' . $task->id, $requestBody)
            ->assertStatus(Response::HTTP_OK)
             ->assertJsonFragment($requestBody);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson($this->uri . '/' . $task->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

}
