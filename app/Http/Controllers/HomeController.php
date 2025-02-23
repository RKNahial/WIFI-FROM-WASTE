<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
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
