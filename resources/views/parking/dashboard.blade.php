@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@push('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .stat-card { background-color: var(--card-bg); padding: 20px; border-radius: 10px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .stat-card h3 { font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; }
    .stat-card .value { font-size: 1.8rem; font-weight: 700; color: var(--primary); }
    .table-container { background-color: var(--card-bg); border-radius: 10px; border: 1px solid var(--border); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .table-header { padding: 20px; border-bottom: 1px solid var(--border); font-weight: 600; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th, td { padding: 15px 20px; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
    th { background-color: #F3F4F6; color: var(--text-muted); font-weight: 500; text-transform: uppercase; font-size: 0.8rem; }
    tbody tr:hover { background-color: #F9FAFB; }
    .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .badge-rekomendasi { background: #D1FAE5; color: #065F46; }
    .badge-alternatif { background: #FEF3C7; color: #92400E; }
    .badge-hindari { background: #FEE2E2; color: #991B1B; }
    .btn-detail { background: #F3F4F6; color: var(--text-main); border: 1px solid var(--border); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-family: 'Poppins', sans-serif; transition: all 0.2s; }
    .btn-detail:hover { background: #E5E7EB; }
    .detail-item { margin-bottom: 15px; }
    .detail-label { font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; }
    .detail-value { font-size: 1.1rem; font-weight: 500; margin-top: 4px; }
</style>
@endpush

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Penggunaan Sistem</h3>
            <div class="value">{{ $totalPencarian }} <span style="font-size: 1rem; font-weight:500; color: #6B7280;">kali</span></div>
        </div>
        <div class="stat-card">
            <h3>Dominasi Kendaraan</h3>
            <div class="value" style="text-transform: capitalize;">
                {{ $kendaraanDominan ? $kendaraanDominan->jenis_kendaraan : '-' }}
            </div>
        </div>
        <div class="stat-card">
            <h3>Rasio Keputusan (Rekomendasi)</h3>
            <div class="value" style="color: #10B981;">
                {{ $statistikKeputusan['Rekomendasi'] ?? 0 }} <span style="font-size: 1rem; font-weight:500; color: #6B7280;">sesi</span>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">Riwayat Pencarian Terbaru</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Waktu (Hari & Jam)</th>
                        <th>Kendaraan</th>
                        <th>Tanggal Akses</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatTerbaru as $riwayat)
                        <tr>
                            <td>
                                <strong>{{ $riwayat->user->name ?? 'Guest' }}</strong><br>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $riwayat->user->email ?? '-' }}</span>
                            </td>
                            <td>
                                <strong>{{ $riwayat->hari }}</strong><br>
                                <span style="color: var(--text-muted); font-size: 0.8rem;">{{ \Carbon\Carbon::parse($riwayat->jam)->format('H:i') }} WIB</span>
                            </td>
                            <td style="text-transform: capitalize;">{{ $riwayat->jenis_kendaraan }}</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">{{ $riwayat->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <button class="btn-detail" 
                                    data-kondisi="{{ $riwayat->kondisi }}"
                                    data-keputusan="{{ $riwayat->hasil_keputusan }}"
                                    data-list="{{ json_encode($riwayat->hasil_list) }}"
                                    onclick="showDetail(this)">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--text-muted);">Belum ada data riwayat pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div class="modal-title">Detail Analisis</div>
            
            <div class="detail-item">
                <div class="detail-label">Estimasi Kondisi Mayoritas</div>
                <div id="modalKondisi" class="detail-value" style="text-transform: capitalize;">-</div>
            </div>

            <div class="detail-item" style="margin-bottom: 20px;">
                <div class="detail-label" style="color: #065F46;">Daftar Rekomendasi</div>
                <ul id="modalRekomendasi" style="padding-left: 15px; font-size: 0.9rem; margin-top: 5px;"></ul>
            </div>

            <div class="detail-item">
                <div class="detail-label" style="color: #92400E;">Daftar Alternatif</div>
                <ul id="modalAlternatif" style="padding-left: 15px; font-size: 0.9rem; margin-top: 5px;"></ul>
            </div>
            
            <button onclick="closeModal()" style="width: 100%; margin-top: 15px; background: var(--primary); color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer;">Tutup</button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showDetail(buttonElement) {
        let kondisi = buttonElement.getAttribute('data-kondisi');
        let rawListData = buttonElement.getAttribute('data-list');
        let listData = rawListData && rawListData !== "null" ? JSON.parse(rawListData) : {};

        document.getElementById('modalKondisi').innerText = kondisi;

        let htmlRekomendasi = listData.Rekomendasi && listData.Rekomendasi.length > 0 
            ? listData.Rekomendasi.map(loc => `<li>${loc}</li>`).join('') 
            : '<li style="color: #9CA3AF;">Tidak ada rekomendasi</li>';
        document.getElementById('modalRekomendasi').innerHTML = htmlRekomendasi;

        let htmlAlternatif = listData.Alternatif && listData.Alternatif.length > 0 
            ? listData.Alternatif.map(loc => `<li>${loc}</li>`).join('') 
            : '<li style="color: #9CA3AF;">Tidak ada alternatif</li>';
        document.getElementById('modalAlternatif').innerHTML = htmlAlternatif;
        
        document.getElementById('detailModal').style.display = "block";
    }

    function closeModal() { document.getElementById('detailModal').style.display = "none"; }
    window.onclick = function(event) { let modal = document.getElementById('detailModal'); if (event.target == modal) { modal.style.display = "none"; } }
</script>
@endpush