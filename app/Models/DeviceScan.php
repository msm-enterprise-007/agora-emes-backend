<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeviceScan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'device_id',
        'mac_address',
        'ip_address',
        'scan_source',
        'is_online',
        'scanned_at',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function attendanceEvents(): HasMany
    {
        return $this->hasMany(AttendanceEvent::class);
    }

    public function macHistories(): HasMany
    {
        return $this->hasMany(MacHistory::class);
    }
}