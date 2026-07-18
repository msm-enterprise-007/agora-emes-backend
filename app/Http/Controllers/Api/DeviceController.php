<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Device::with('user')
                ->orderByDesc('created_at')
                ->get()
        );
    }

    public function store(StoreDeviceRequest $request): JsonResponse
    {
        $device = Device::create($request->validated());

        return response()->json(
            $device->load('user'),
            201
        );
    }

    public function show(Device $device): JsonResponse
    {
        return response()->json(
            $device->load('user')
        );
    }

    public function update(UpdateDeviceRequest $request, Device $device): JsonResponse
    {
        $device->update($request->validated());

        return response()->json(
            $device->load('user')
        );
    }

    public function destroy(Device $device): JsonResponse
    {
        $device->delete();

        return response()->json([
            'message' => 'Appareil supprimé avec succès.'
        ]);
    }
}