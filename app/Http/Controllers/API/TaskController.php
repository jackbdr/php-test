<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Task::all();

        return response()->json([
            'success' => true,
            'data' => TaskResource::collection($tasks),
            'message' => 'Tasks retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        $editUrl = URL::temporarySignedRoute(
            'tasks.edit.signed',
            now()->addMinutes(60), // expire in 60 minutes
            ['task' => $task->id]
        );

        $deleteUrl = URL::temporarySignedRoute(
            'tasks.delete.signed',
            now()->addMinutes(60),
            ['task' => $task->id]
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'edit_url' => $editUrl,
            'delete_url' => $deleteUrl,
            'message' => 'Task created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message' => 'Task retrieved successfully.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function signedUpdate(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|min:3|max:100',
            'description' => 'sometimes|string|min:10|max:5000',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
            'message' => 'Task updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function signedDestroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }
}
