<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Add your data fetching logic here
        $data = [
            'devices' => [], // Your devices data
            'bottleStats' => [
                'total' => 0,
                'today' => 0
            ],
            'bandwidthStats' => [
                'total' => '0 B',
                'today' => '0 B'
            ],
            'routerBandwidth' => [
                'total_rate' => '0 Mbps',
                'rx_rate' => '0 Mbps',
                'tx_rate' => '0 Mbps'
            ],
            'activeUsersCount' => 0
        ];

        return view('dashboard.index', $data);
    }
}