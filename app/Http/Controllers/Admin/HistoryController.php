<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParkingHistory;
use Barryvdh\DomPDF\Facade\Pdf;

class HistoryController extends Controller
{
    public function index(Request $request)
{
    $query = ParkingHistory::with('user');

    if ($request->filled('search')) {
        $keyword = $request->search;
        $query->where(function($q) use ($keyword) {
            $q->where('lokasi', 'like', '%' . $keyword . '%')
              ->orWhereHas('user', function($u) use ($keyword) {
                  $u->where('name', 'like', '%' . $keyword . '%');
              });
        });
    }

    // Filter Tanggal
    if ($request->filled('tgl_mulai')) { $query->whereDate('created_at', '>=', $request->tgl_mulai); }
    if ($request->filled('tgl_selesai')) { $query->whereDate('created_at', '<=', $request->tgl_selesai); }

    // Filter Hari & Kendaraan
    if ($request->filled('hari')) { $query->where('hari', $request->hari); }
    if ($request->filled('kendaraan')) { $query->where('jenis_kendaraan', $request->kendaraan); }

    // FILTER KATEGORI (Civitas vs Tamu)
    if ($request->filled('kategori')) {
        $query->whereHas('user', function($q) use ($request) {
            if ($request->kategori == 'civitas') {
                $q->where('email', 'like', '%@mahasiswa.pcr.ac.id')
                  ->orWhere('email', 'like', '%@pcr.ac.id');
            } else {
                $q->where('email', 'not like', '%@mahasiswa.pcr.ac.id')
                  ->where('email', 'not like', '%@pcr.ac.id');
            }
        });
    }

    $riwayatTerbaru = $query->orderBy('created_at', 'desc')->paginate(10);
    return view('admin.history', compact('riwayatTerbaru'));
}

    public function exportPdf(Request $request)
    {
        $query = ParkingHistory::with('user');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($u) use ($request) {
                      $u->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        if ($request->filled('kendaraan')) { $query->where('jenis_kendaraan', $request->kendaraan); }
        if ($request->filled('hari')) { $query->where('hari', $request->hari); }
        if ($request->filled('tgl_mulai')) { $query->whereDate('created_at', '>=', $request->tgl_mulai); }
        if ($request->filled('tgl_selesai')) { $query->whereDate('created_at', '<=', $request->tgl_selesai); }

        $riwayat = $query->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('admin.pdf.history', compact('riwayat'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Riwayat_Parkir_'.date('Y-m-d').'.pdf');
    }
}
