{{-- resources/views/public/history.blade.php --}}
@extends('layouts.public')

@section('title', 'Riwayat Pencarian Saya')

@section('content')
<div class="history-page">
    <div class="history-container">
        <div class="history-header">
            <div>
                <h1><i class="fas fa-history"></i> Riwayat Pencarian Saya</h1>
                <p>Semua rekomendasi parkir yang pernah Anda cari</p>
            </div>
            <a href="{{ route('parking.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Pencarian
            </a>
        </div>

        @if($riwayat->count() > 0)
            <div class="table-wrapper">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Waktu Akses</th>
                            <th>Hari & Jam</th>
                            <th>Kendaraan</th>
                            <th>Rekomendasi Utama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $item)
                        <tr>
                            <td class="td-date">
                                <i class="far fa-calendar-alt"></i> {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td>
                                <i class="fas fa-calendar-week"></i> {{ $item->hari }}
                                <span class="jam"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB</span>
                            </td>
                            <td class="td-vehicle">
                                @if($item->jenis_kendaraan == 'mobil')
                                    <i class="fas fa-car"></i> Mobil
                                @else
                                    <i class="fas fa-motorcycle"></i> Motor
                                @endif
                            </td>
                            <td>
                                <span class="badge-rekomendasi">
                                    <i class="fas fa-map-pin"></i> {{ $item->lokasi }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 px-2">
    {{ $riwayat->links() }}
</div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada riwayat pencarian.</p>
                <a href="{{ route('parking.index') }}" class="btn-primary">Mulai Prediksi Sekarang</a>
            </div>
        @endif
    </div>
</div>
@endsection

