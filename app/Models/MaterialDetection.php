<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialDetection extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_type',
        'count',
        'detected_at'
    ];

    protected $casts = [
        'detected_at' => 'datetime'
    ];
}