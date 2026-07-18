<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function login(
        LoginRequest $request,
        AuthService $service
    ): JsonResponse {
    
        $result = $service->login(
            $request->validated()
        );
    
        return response()->json($result);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(
        AuthService $service
    ): JsonResponse {
    
        $service->logout(auth()->user());
    
        return response()->json([
            'message' => 'Déconnexion réussie.'
        ]);
    }
}