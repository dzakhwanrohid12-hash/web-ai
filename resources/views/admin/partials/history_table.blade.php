<table class="history-table" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th class="history-th">Tgl Pencarian</th>
            <th class="history-th">Pengguna</th>
            <th class="history-th">Input Waktu</th>
            <th class="history-th">Lokasi Rekomendasi</th>
            <th class="history-th">Kendaraan</th>
            <th class="history-th">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($riwayatTerbaru as $riwayat)
            <tr style="border-bottom: 1px solid #F1F5F9;">
                <td class="history-td">
                    <strong>{{ $riwayat->created_at->format('d M Y') }}</strong><br>
                    <span style="font-size: 0.75rem; color: #94A3B8;">{{ $riwayat->created_at->format('H:i:s') }} WIB</span>
                </td>

                <td class="history-td">
                    @if($riwayat->user)
                        <strong>{{ $riwayat->user->name }}</strong><br>
                        <span style="font-size: 0.75rem; color: #64748B;">{{ $riwayat->user->email }}</span><br>

                        {{-- Logika Label Otomatis --}}
                        @if(str_ends_with($riwayat->user->email, '@mahasiswa.pcr.ac.id') || str_ends_with($riwayat->user->email, '@pcr.ac.id'))
                            <span style="background: #DBEAFE; color: #1E40AF; padding: 2px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 700; display: inline-block; margin-top: 4px;">
                                <i class="fas fa-university" style="font-size: 0.6rem;"></i> CIVITAS PCR
                            </span>
                        @else
                            <span style="background: #F1F5F9; color: #475569; padding: 2px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 700; display: inline-block; margin-top: 4px;">
                                <i class="fas fa-globe" style="font-size: 0.6rem;"></i> TAMU PUBLIK
                            </span>
                        @endif
                    @else
                        <strong style="color: #94A3B8;">Guest</strong><br>
                        <span style="background: #FFF7ED; color: #9A3412; padding: 2px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 700;">ANONYMOUS</span>
                    @endif
                </td>

                <td class="history-td">
                    <strong>{{ $riwayat->hari }}</strong><br>
                    <span style="font-size: 0.75rem; color: #64748B;">{{ \Carbon\Carbon::parse($riwayat->jam)->format('H:i') }} WIB</span>
                </td>

                <td class="history-td">
                    <span style="font-weight: 700; color: #10B981;">{{ $riwayat->lokasi }}</span>
                </td>

                <td class="history-td" style="text-transform: capitalize;">{{ $riwayat->jenis_kendaraan }}</td>

                <td class="history-td">
                    <button class="btn-detail"
                            style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem;"
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
                <td colspan="6" style="text-align: center; padding: 40px; color: #94A3B8;">Belum ada data riwayat pencarian.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div style="padding: 15px 20px;">
    {{ $riwayatTerbaru->appends(request()->query())->links('pagination::bootstrap-4') }}
</div>
