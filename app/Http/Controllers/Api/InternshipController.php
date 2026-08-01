<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInternshipRequest;
use App\Http\Requests\UpdateInternshipRequest;
use App\Models\Internship;
use Illuminate\Http\JsonResponse;

class InternshipController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Internship::with(['user', 'supervisor'])
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function store(StoreInternshipRequest $request): JsonResponse
    {
        $internship = Internship::create($request->validated());

        return response()->json(
            $internship->load('user'),
            201
        );
    }

    public function show(Internship $internship): JsonResponse
    {
        return response()->json(
            $internship->load('user')
        );
    }

    public function update(UpdateInternshipRequest $request, Internship $internship): JsonResponse
    {
        $internship->update($request->validated());

        return response()->json(
            $internship->load('user')
        );
    }

    public function destroy(Internship $internship): \Illuminate\Http\Response
    {
        $internship->delete();
    
        return response()->noContent();
    }
}