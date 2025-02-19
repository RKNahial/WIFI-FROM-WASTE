<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentController extends Model
{
    protected $fillable = [
        'name',
        'monthly_sales',
        'last_sales',
        'total_sales',
        'status'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}