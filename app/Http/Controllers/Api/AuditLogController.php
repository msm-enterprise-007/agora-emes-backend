<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuditLogRequest;
use App\Http\Requests\UpdateAuditLogRequest;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            AuditLog::with('user')
                ->latest('performed_at')
                ->get()
        );
    }

    public function store(StoreAuditLogRequest $request): JsonResponse
    {
        $auditLog = AuditLog::create($request->validated());

        return response()->json(
            $auditLog->load('user'),
            201
        );
    }

    public function show(AuditLog $auditLog): JsonResponse
    {
        return response()->json(
            $auditLog->load('user')
        );
    }

    public function update(UpdateAuditLogRequest $request, AuditLog $auditLog): JsonResponse
    {
        $auditLog->update($request->validated());

        return response()->json(
            $auditLog->load('user')
        );
    }

    public function destroy(AuditLog $auditLog): JsonResponse
    {
        $auditLog->delete();

        return response()->json([
            'message' => 'Journal d’audit supprimé avec succès.'
        ]);
    }
}