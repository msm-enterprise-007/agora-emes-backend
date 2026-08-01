<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MacHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'device_scan_id',
        'old_mac_address',
        'new_mac_address',
        'change_type',
        'description',
        'detected_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function deviceScan(): BelongsTo
    {
        return $this->belongsTo(DeviceScan::class);
    }
}