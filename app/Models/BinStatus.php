<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bin_type',
        'is_full',
        'fill_percentage',
        'last_checked'
    ];

    protected $casts = [
        'is_full' => 'boolean',
        'last_checked' => 'datetime'
    ];
}