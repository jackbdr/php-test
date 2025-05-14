<?php

namespace Tests\Unit\app\Http\Controllers\API;

use App\Http\Controllers\API\TaskController;
use App\Http\Requests\ListTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use Tests\DataProviders\TaskDataProviders;
use Tests\TestCase;
use Mockery;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, TaskDataProviders;

    #[DataProvider('indexRequestProvider')] public function test_index_returns_expected_tasks(bool $withDeleted, int $expectedCount)
    {
        Task::factory()->count(2)->create();
        Task::factory()->create()->delete();

        $mockRequest = Mockery::mock(ListTaskRequest::class);
        $mockRequest->shouldReceive('validated')->once();
        $mockRequest->shouldReceive('boolean')->with('with_deleted')->andReturn($withDeleted);

        $controller = new TaskController();
        $response = $controller->index($mockRequest);
        $responseData = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Tasks retrieved successfully.', $responseData['message']);
        $this->assertCount($expectedCount, $responseData['data']);
    }

    public function test_store_creates_task_and_returns_json_response()
    {
        $validData = ['name' => 'Test Task', 'description' => 'Test Description'];

        $mockRequest = Mockery::mock(TaskRequest::class);
        $mockRequest->shouldReceive('validated')->once()->andReturn($validData);

        $controller = new TaskController();
        $response = $controller->store($mockRequest);
        $responseData = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->status());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Task created successfully.', $responseData['message']);
        $this->assertEquals($validData['name'], $responseData['data']['name']);
        $this->assertEquals($validData['description'], $responseData['data']['description']);
    }

    public function test_show_returns_task_as_json_response()
    {
        $task = Task::factory()->create();

        $controller = new TaskController();
        $response = $controller->show($task);
        $responseData = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Task retrieved successfully.', $responseData['message']);
        $this->assertEquals($task->id, $responseData['data']['id']);
        $this->assertEquals($task->name, $responseData['data']['name']);
        $this->assertEquals($task->description, $responseData['data']['description']);
    }


    public function test_signed_update_updates_task_and_returns_json_response()
    {
        $task = Task::factory()->create([
            'name' => 'Old name',
            'description' => 'Old description',
        ]);

        $validatedData = [
            'name' => 'New name',
            'description' => 'New description',
        ];

        $mockRequest = Mockery::mock(TaskRequest::class);
        $mockRequest->shouldReceive('validated')
            ->once()
            ->andReturn($validatedData);

        $controller = new TaskController();
        $response = $controller->signedUpdate($mockRequest, $task);
        $responseData = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Task updated successfully.', $responseData['message']);
        $this->assertEquals('New name', $responseData['data']['name']);
        $this->assertEquals('New description', $responseData['data']['description']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'New name',
            'description' => 'New description',
        ]);
    }

    public function test_signed_destroy_soft_deletes_task_and_returns_json_response()
    {
        $task = Task::factory()->create();

        $controller = new TaskController();

        $response = $controller->signedDestroy($task);
        $responseData = $response->getData(true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Task deleted successfully.', $responseData['message']);

        $this->assertSoftDeleted('tasks', [
            'id' => $task->id,
        ]);
    }
}
