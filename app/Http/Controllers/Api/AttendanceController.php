<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\AttendanceSession;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            AttendanceSession::with('internship')
                ->orderByDesc('attendance_date')
                ->get()
        );
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $attendance = AttendanceSession::create($request->validated());

        return response()->json(
            $attendance->load('internship'),
            201
        );
    }

    public function show(AttendanceSession $attendance): JsonResponse
    {
        return response()->json(
            $attendance->load('internship')
        );
    }

    public function update(UpdateAttendanceRequest $request, AttendanceSession $attendance): JsonResponse
    {
        $attendance->update($request->validated());

        return response()->json(
            $attendance->load('internship')
        );
    }

   public function destroy(AttendanceSession $attendance): \Illuminate\Http\Response
{
    $attendance->delete();

    return response()->noContent();
}
}