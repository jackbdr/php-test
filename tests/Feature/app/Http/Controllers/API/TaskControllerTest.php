<?php

namespace Tests\Feature\app\Http\Controllers\API;

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

    #[DataProvider('storeRequestProvider')] public function test_store_fails_with_invalid_data(
        string $name,
        string $description,
        array $expectedErrors
    )
    {
        $invalidData = ['name' => $name, 'description' => $description];

        $response = $this->postJson(route('tasks.store'), $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public function test_signed_update_fails_with_invalid_signed_url()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => 'Test Task',
            'description' => 'Test Description',
        ]);

        $responseData = $response->json();
        $editUrl = $responseData['edit_url'];

        $invalidEditUrl = substr_replace($editUrl, 'zzzzz', -5);

        $updateResponse = $this->putJson($invalidEditUrl, [
            'name' => 'Updated Task',
            'description' => 'Updated Description',
        ]);

        $updateResponse->assertStatus(403);
    }

    public function test_signed_delete_fails_with_invalid_signed_url()
    {
        $response = $this->postJson('/api/tasks', [
            'name' => 'Test Task',
            'description' => 'Test Description',
        ]);

        $responseData = $response->json();
        $deleteUrl = $responseData['delete_url'];

        $invalidDeleteUrl = substr_replace($deleteUrl, 'zzzzz', -5);

        $deleteResponse = $this->deleteJson($invalidDeleteUrl);

        $deleteResponse->assertStatus(403);
    }
}
