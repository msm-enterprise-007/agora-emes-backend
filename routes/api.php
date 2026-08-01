<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\InternshipApplicationController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskReportController;
use App\Http\Controllers\Api\FormationController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\ConfigController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\AttendanceActionController;
use App\Http\Controllers\Api\NetworkController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/internship-applications',[InternshipApplicationController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Illuminate\Http\Request $request) {
    return $request->user();
    });
    Route::get('/internship-applications',[InternshipApplicationController::class, 'index']);
    Route::get('/internship-applications/{internshipApplication}',[InternshipApplicationController::class, 'show']);
    Route::patch('/internship-applications/{internshipApplication}/review',[InternshipApplicationController::class, 'review']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('internships', InternshipController::class);
    Route::apiResource('devices', DeviceController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('task-reports', TaskReportController::class);
    Route::apiResource('formations', FormationController::class);
    Route::apiResource('notes', NoteController::class);
    Route::apiResource('configs', ConfigController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('audit-logs', AuditLogController::class);
    Route::post('internships/{internship}/check-in',[AttendanceActionController::class, 'checkIn']);
    Route::post('attendance-sessions/{attendanceSession}/break-out',[AttendanceActionController::class, 'breakOut']);
    Route::post('attendance-sessions/{attendanceSession}/break-in',[AttendanceActionController::class, 'breakIn']);
    Route::post('attendance-sessions/{attendanceSession}/check-out',[AttendanceActionController::class, 'checkOut']);

    Route::prefix('network')->group(function () {
        Route::get('/info', [NetworkController::class, 'info']);
        Route::get('/devices', [NetworkController::class, 'devices']);
        Route::post('/block/{mac}', [NetworkController::class, 'block']);
        Route::post('/authorize/{mac}', [NetworkController::class, 'authorize']);
        Route::get('/leases', [NetworkController::class, 'leases']);
        Route::get('/arp', [NetworkController::class, 'arp']);
        Route::get('/neighbors', [NetworkController::class, 'neighbors']);
        Route::get('/wireless-clients', [NetworkController::class, 'wirelessClients']);
        Route::get('/hotspot/users', [NetworkController::class, 'hotspotUsers']);
        Route::get('/hotspot/active', [NetworkController::class, 'activeHotspotUsers']);
        Route::get('/hotspot/servers', [NetworkController::class, 'hotspotServers']);
        Route::get('/health', [NetworkController::class, 'health']);
        Route::get('/clock', [NetworkController::class, 'clock']);
        Route::get('/dns', [NetworkController::class, 'dns']);
        Route::get('/ip-addresses', [NetworkController::class, 'ipAddresses']);
        Route::get('/routes', [NetworkController::class, 'routes']);
        Route::get('/firewall/rules', [NetworkController::class, 'firewallRules']);
        Route::get('/bridge/hosts', [NetworkController::class, 'bridgeHosts']);
        Route::get('/bridge/ports', [NetworkController::class, 'bridgePorts']);
        Route::get('/logs', [NetworkController::class, 'logs']);
        Route::post('/reboot', [NetworkController::class, 'reboot']);
        Route::post('/shutdown', [NetworkController::class, 'shutdown']);
        Route::post('/backup', [NetworkController::class, 'backup']);
        Route::get('/backups', [NetworkController::class, 'backups']); 
        Route::get('/status', [NetworkController::class, 'status']);     
    });


});