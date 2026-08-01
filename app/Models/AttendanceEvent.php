<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceEvent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'attendance_session_id',
        'device_scan_id',
        'event_type',
        'source',
        'event_time',
        'metadata',
        'remarks',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'metadata' => 'array',
    ];

    public function attendanceSession(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    public function deviceScan(): BelongsTo
    {
        return $this->belongsTo(DeviceScan::class);
    }
}