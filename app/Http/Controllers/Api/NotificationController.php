<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Notification::with('user')
                ->latest()
                ->get()
        );
    }

    public function store(StoreNotificationRequest $request): JsonResponse
    {
        $notification = Notification::create($request->validated());

        return response()->json(
            $notification->load('user'),
            201
        );
    }

    public function show(Notification $notification): JsonResponse
    {
        return response()->json(
            $notification->load('user')
        );
    }

    public function update(UpdateNotificationRequest $request, Notification $notification): JsonResponse
    {
        $notification->update($request->validated());

        return response()->json(
            $notification->load('user')
        );
    }

   public function destroy(Notification $notification): \Illuminate\Http\Response
{
    $notification->delete();

    return response()->noContent();
}
}