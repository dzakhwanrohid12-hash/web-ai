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

@push('styles')
<style>
    .dashboard-analytics {
        padding: 20px 24px;
        max-width: 1400px;
        margin: 0 auto;
    }
    /* Grid 3 card */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }
    .stat-card {
        background: white;
        border-radius: 24px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border, #E2E8F0);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
    }
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }
    .stat-icon.primary {
        background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
        color: #4F46E5;
    }
    .stat-icon.success {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #10B981;
    }
    .stat-icon.warning {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: #F59E0B;
    }
    .stat-content {
        flex: 1;
    }
    .stat-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted, #64748B);
        display: block;
        margin-bottom: 8px;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-main, #1E293B);
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .stat-unit {
        font-size: 0.7rem;
        color: var(--text-muted, #64748B);
    }
    /* Charts grid 2 kolom */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 28px;
    }
    .chart-card {
        background: white;
        border-radius: 24px;
        padding: 20px;
        border: 1px solid var(--border, #E2E8F0);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }
    .chart-card h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main, #1E293B);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chart-card h3 i {
        color: var(--primary, #4F46E5);
    }
    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .charts-grid {
            grid-template-columns: 1fr;
        }
        .dashboard-analytics {
            padding: 16px;
        }
    }
</style>
@endpush
