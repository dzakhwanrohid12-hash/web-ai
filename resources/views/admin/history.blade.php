@extends('layouts.admin')

@section('title', 'Riwayat Pencarian')
@section('page_title', 'Riwayat Penggunaan Sistem')

@section('content')
<div class="admin-card" style="background: white; padding: 25px; border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 4px 15px rgba(0,0,0,0.02);">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <h3 style="color: #1E293B; margin: 0; font-size: 1.2rem;">Data Riwayat Prediksi</h3>

       <form action="{{ route('admin.history') }}" method="GET" id="filterForm" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">

        <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 0.7rem; font-weight: 700; color: #64748B; display: block; margin-bottom: 5px;">PENCARIAN</label>
            <input type="text" name="search" placeholder="Cari nama atau lokasi..." value="{{ request('search') }}"
                   style="width: 100%; padding: 9px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem; outline: none;">
        </div>
    {{-- Filter Tanggal tetap sama --}}
    <div style="display: flex; flex-direction: column;">
        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-bottom: 2px;">DARI TANGGAL</span>
        <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" onchange="this.form.submit()" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem;">
    </div>
    <div style="display: flex; flex-direction: column;">
        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-bottom: 2px;">SAMPAI TANGGAL</span>
        <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" onchange="document.getElementById('filterForm').submit()"
               style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem; outline: none;">
    </div>

    {{-- Filter Kategori (BARU) --}}
    <div style="display: flex; flex-direction: column;">
        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-bottom: 2px;">KATEGORI USER</span>
        <select name="kategori" onchange="this.form.submit()" style="padding: 8px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem;">
            <option value="">Semua Kategori</option>
            <option value="civitas" {{ request('kategori') == 'civitas' ? 'selected' : '' }}>Civitas PCR</option>
            <option value="tamu" {{ request('kategori') == 'tamu' ? 'selected' : '' }}>Tamu Publik</option>
        </select>
    </div>

    {{-- Dropdown Hari & Kendaraan tetap sama --}}
    <select name="hari" onchange="this.form.submit()" style="padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem; align-self: flex-end;">
        <option value="">Semua Hari</option>
        <option value="Senin" {{ request('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
        <option value="Selasa" {{ request('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
    </select>

    <div>
            <label style="font-size: 0.7rem; font-weight: 700; color: #64748B; display: block; margin-bottom: 5px;">KENDARAAN</label>
            <select name="kendaraan" onchange="this.form.submit()" style="padding: 9px 12px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.85rem; background: white;">
                <option value="">Semua</option>
                <option value="sepeda motor" {{ request('kendaraan') == 'sepeda motor' ? 'selected' : '' }}>Motor</option>
                <option value="mobil" {{ request('kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
            </select>
        </div>
    {{-- Tombol PDF --}}
    <a href="{{ route('admin.history.pdf', request()->all()) }}" style="background: #EF4444; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; align-self: flex-end;">
        <i class="fas fa-file-pdf"></i> Unduh PDF
    </a>
</form>
    </div>

    <div class="table-responsive" id="tableContainer">
        @include('admin.partials.history_table', ['riwayatTerbaru' => $riwayatTerbaru])
    </div>
</div>

<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 700px; padding: 30px;">
        <span class="close-modal" onclick="closeModal()">&times;</span>

        <div style="font-size: 1.4rem; font-weight: 700; margin-bottom: 20px; color: #1E2937; border-bottom: 2px solid #F1F5F9; padding-bottom: 12px;">
            Detail Riwayat Analisis Parkir
        </div>

        <div style="background: #F8FAFC; padding: 18px 20px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 25px;">
            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Estimasi Kondisi Kampus (Mayoritas)</div>
            <div id="modalKondisi" style="font-size: 1.3rem; font-weight: 700; text-transform: capitalize; margin-top: 5px;">-</div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">

            <div style="background: #F0FDF4; border: 1px solid #BBF7D0; padding: 18px; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #166534; margin-bottom: 12px;"><i class="fas fa-check-circle"></i> Direkomendasikan (Sepi)</div>
                <ul id="modalRekomendasi" style="padding-left: 18px; font-size: 0.85rem; color: #15803d; margin: 0; line-height: 1.6;"></ul>
            </div>

            <div style="background: #FFFBEB; border: 1px solid #FDE68A; padding: 18px; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #92400E; margin-bottom: 12px;"><i class="fas fa-exclamation-circle"></i> Alternatif (Sedang)</div>
                <ul id="modalAlternatif" style="padding-left: 18px; font-size: 0.85rem; color: #b45309; margin: 0; line-height: 1.6;"></ul>
            </div>

            <div style="background: #FEF2F2; border: 1px solid #FECACA; padding: 18px; border-radius: 12px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #B91C1C; margin-bottom: 12px;"><i class="fas fa-times-circle"></i> Area Padat (Hindari)</div>
                <ul id="modalHindari" style="padding-left: 18px; font-size: 0.85rem; color: #b91c1c; margin: 0; line-height: 1.6;"></ul>
            </div>

        </div>
    </div>
</div>
@endsection
