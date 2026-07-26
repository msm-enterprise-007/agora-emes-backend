<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use App\Models\Formation;
use Illuminate\Http\JsonResponse;

class FormationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Formation::with('supervisor')
                ->latest()
                ->get()
        );
    }

    public function store(StoreFormationRequest $request): JsonResponse
    {
        $formation = Formation::create($request->validated());

        return response()->json(
            $formation->load('supervisor'),
            201
        );
    }

    public function show(Formation $formation): JsonResponse
    {
        return response()->json(
            $formation->load('supervisor')
        );
    }

    public function update(UpdateFormationRequest $request, Formation $formation): JsonResponse
    {
        $formation->update($request->validated());

        return response()->json(
            $formation->load('supervisor')
        );
    }

    public function destroy(Formation $formation): \Illuminate\Http\Response
{
    $formation->delete();

    return response()->noContent();
}
}