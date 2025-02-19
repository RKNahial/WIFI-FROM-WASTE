<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleController extends Model
{
    protected $fillable = [
        'vendor_id',
        'voucher_code',
        'amount',
        'customer_mac',
        'customer_ip',
        'validity',
        'extended'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}