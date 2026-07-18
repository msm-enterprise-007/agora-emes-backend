<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Services\Attendance\AttendanceService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CheckInRequest;
use App\Models\AttendanceSession;

class AttendanceActionController extends Controller
{
    public function checkIn(
        CheckInRequest $request,
        Internship $internship,
        AttendanceService $service
    ): JsonResponse {

        $request->validate([
            'mac_address' => ['required', 'string'],
        ]);

        $session = $service->checkIn(
            $internship,
            $request->mac_address
        );

        return response()->json($session);
    }

    public function breakOut(
        AttendanceSession $attendanceSession,
        AttendanceService $service
    ): JsonResponse {
    
        return response()->json(
            $service->breakOut($attendanceSession)
        );
    }
    
    public function breakIn(
        AttendanceSession $attendanceSession,
        AttendanceService $service
    ): JsonResponse {
    
        return response()->json(
            $service->breakIn($attendanceSession)
        );
    }
    
    public function checkOut(
        AttendanceSession $attendanceSession,
        AttendanceService $service
    ): JsonResponse {
    
        return response()->json(
            $service->checkOut($attendanceSession)
        );
    }
}