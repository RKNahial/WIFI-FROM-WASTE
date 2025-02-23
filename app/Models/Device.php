<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mac_address',
        'status',
        'bandwidth_used',
        'last_seen',
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];
}
