<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Role::orderBy('name')->get());
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->validated());

        return response()->json($role, 201);
    }

    public function show(Role $role): JsonResponse
    {
        return response()->json($role);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update($request->validated());

        return response()->json($role);
    }

   public function destroy(Role $role): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
{
    if ($role->users()->exists()) {
        return response()->json([
            'message' => 'Impossible de supprimer un rôle déjà attribué à des utilisateurs.'
        ], 422);
    }

    $role->delete();

    return response()->noContent();
}
}