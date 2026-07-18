<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'device_name',
        'device_type',
        'brand',
        'model',
        'mac_address',
        'is_authorized',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scans(): HasMany
    {
        return $this->hasMany(DeviceScan::class);
    }

    public function macHistories(): HasMany
    {
        return $this->hasMany(MacHistory::class);
    }
}