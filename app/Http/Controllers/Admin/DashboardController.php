<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingHistory;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPencarian = ParkingHistory::count();

        $dataKendaraan = ParkingHistory::select('jenis_kendaraan')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('jenis_kendaraan')
            ->get();

        $dataKeputusan = ParkingHistory::select('hasil_keputusan')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('hasil_keputusan')
            ->get();

        return view('admin.dashboard', compact('totalPencarian', 'dataKendaraan', 'dataKeputusan'));
    }
}
