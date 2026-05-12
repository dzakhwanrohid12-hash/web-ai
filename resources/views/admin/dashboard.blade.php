@extends('layouts.admin')

@section('title', 'Dashboard Analitik')
@section('page_title', 'Dashboard Statistik')

@section('content')
<div class="dashboard-analytics">
    {{-- Cards Statistik --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Total Penggunaan Sistem</span>
                <div class="stat-value">{{ $totalPencarian }}</div>
                <span class="stat-unit">kali pencarian</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Dominasi Kendaraan</span>
                <div class="stat-value" style="text-transform: capitalize;">
                    {{ $dataKendaraan->sortByDesc('count')->first()->jenis_kendaraan ?? 'Belum ada data' }}
                </div>
                <span class="stat-unit">terbanyak diprediksi</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <span class="stat-label">Rekomendasi Terbanyak</span>
                <div class="stat-value">
                    {{ $dataKeputusan->sortByDesc('count')->first()->hasil_keputusan ?? 'Belum ada data' }}
                </div>
                <span class="stat-unit">hasil prediksi</span>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="charts-grid">
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Perbandingan Jenis Kendaraan</h3>
            <div class="chart-container">
                <canvas id="kendaraanChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Distribusi Hasil Keputusan Model</h3>
            <div class="chart-container">
                <canvas id="keputusanChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Kendaraan (Doughnut)
        const rawDataKendaraan = @json($dataKendaraan);
        const labelKendaraan = rawDataKendaraan.map(item => item.jenis_kendaraan.toUpperCase());
        const jumlahKendaraan = rawDataKendaraan.map(item => item.count);

        new Chart(document.getElementById('kendaraanChart'), {
            type: 'doughnut',
            data: {
                labels: labelKendaraan,
                datasets: [{
                    data: jumlahKendaraan,
                    backgroundColor: ['#4F46E5', '#38BDF8'],
                    borderWidth: 0,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '70%'
            }
        });

        // Chart Keputusan (Bar)
        const rawDataKeputusan = @json($dataKeputusan);
        const labelKeputusan = rawDataKeputusan.map(item => item.hasil_keputusan);
        const jumlahKeputusan = rawDataKeputusan.map(item => item.count);
        const warnaKeputusan = labelKeputusan.map(label => {
            if(label === 'Rekomendasi') return '#10B981';
            if(label === 'Alternatif') return '#F59E0B';
            return '#EF4444';
        });

        new Chart(document.getElementById('keputusanChart'), {
            type: 'bar',
            data: {
                labels: labelKeputusan,
                datasets: [{
                    label: 'Jumlah Prediksi',
                    data: jumlahKeputusan,
                    backgroundColor: warnaKeputusan,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
