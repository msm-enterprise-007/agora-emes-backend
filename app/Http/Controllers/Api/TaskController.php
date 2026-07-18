<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Task::with(['internship', 'assignedBy'])
                ->latest()
                ->get()
        );
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        return response()->json(
            $task->load(['internship', 'assignedBy']),
            201
        );
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json(
            $task->load(['internship', 'assignedBy'])
        );
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());

        return response()->json(
            $task->load(['internship', 'assignedBy'])
        );
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Tâche supprimée avec succès.'
        ]);
    }
}