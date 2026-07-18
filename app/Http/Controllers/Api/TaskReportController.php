<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskReportRequest;
use App\Http\Requests\UpdateTaskReportRequest;
use App\Models\TaskReport;
use Illuminate\Http\JsonResponse;

class TaskReportController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            TaskReport::with(['task', 'reviewer'])
                ->latest()
                ->get()
        );
    }

    public function store(StoreTaskReportRequest $request): JsonResponse
    {
        $report = TaskReport::create($request->validated());

        return response()->json(
            $report->load(['task', 'reviewer']),
            201
        );
    }

    public function show(TaskReport $taskReport): JsonResponse
    {
        return response()->json(
            $taskReport->load(['task', 'reviewer'])
        );
    }

    public function update(UpdateTaskReportRequest $request, TaskReport $taskReport): JsonResponse
    {
        $taskReport->update($request->validated());

        return response()->json(
            $taskReport->load(['task', 'reviewer'])
        );
    }

    public function destroy(TaskReport $taskReport): JsonResponse
    {
        $taskReport->delete();

        return response()->json([
            'message' => 'Compte rendu supprimé avec succès.'
        ]);
    }
}