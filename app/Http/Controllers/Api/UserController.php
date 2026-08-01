<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            User::with('role')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get()
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json($user->load('role'), 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user->load('role'));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json($user->load('role'));
    }

    public function destroy(User $user): \Illuminate\Http\Response
    {
        $user->delete();
    
        return response()->noContent();
    }
}