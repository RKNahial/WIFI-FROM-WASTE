<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Sale;
use App\Services\MikrotikService;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    public function index()
    {
        $vendors = Vendor::with('sales')->get();
        $totalSales = Sale::sum('amount');
        $todaySales = Sale::whereDate('created_at', today())->sum('amount');
        $activeUsers = $this->mikrotik->getActiveUsers();

        return view('vendors.index', compact('vendors', 'totalSales', 'todaySales', 'activeUsers'));
    }

    public function generateVoucher(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric',
            'validity' => 'required|string',
            'profile' => 'required|string'
        ]);

        // Generate random voucher code
        $voucherCode = strtoupper(substr(md5(microtime()), 0, 8));
        
        // Create voucher in Mikrotik
        $this->mikrotik->createVoucher(
            $voucherCode,
            $voucherCode, // using same code as password
            $validated['profile'],
            $validated['validity']
        );

        // Record sale
        $sale = Sale::create([
            'vendor_id' => $validated['vendor_id'],
            'voucher_code' => $voucherCode,
            'amount' => $validated['amount'],
            'validity' => $validated['validity']
        ]);

        // Update vendor sales
        $vendor = Vendor::find($validated['vendor_id']);
        $vendor->total_sales += $validated['amount'];
        $vendor->monthly_sales += $validated['amount'];
        $vendor->last_sales = $validated['amount'];
        $vendor->save();

        return response()->json([
            'success' => true,
            'voucher' => $voucherCode,
            'sale' => $sale
        ]);
    }
}