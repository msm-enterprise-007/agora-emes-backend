<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfigRequest;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Config::latest()->get()
        );
    }

    public function store(StoreConfigRequest $request): JsonResponse
    {
        $config = Config::create($request->validated());

        return response()->json($config, 201);
    }

    public function show(Config $config): JsonResponse
    {
        return response()->json($config);
    }

    public function update(UpdateConfigRequest $request, Config $config): JsonResponse
    {
        $config->update($request->validated());

        return response()->json($config);
    }

   public function destroy(Config $config): Response
{
    $config->delete();

    return response()->noContent();
}
}