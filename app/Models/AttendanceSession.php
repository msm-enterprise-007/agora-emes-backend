<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceSession extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'internship_id',
        'attendance_date',
        'check_in_at',
        'break_out_at',
        'break_in_at',
        'check_out_at',
        'worked_minutes',
        'break_minutes',
        'late_minutes',
        'arrival_status',
        'status',
        'is_verified',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_at' => 'datetime',
        'break_out_at' => 'datetime',
        'break_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(AttendanceEvent::class);
    }
}