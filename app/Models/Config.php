<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Config extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];
}