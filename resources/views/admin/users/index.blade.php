@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="user-management">
    {{-- Header dengan statistik --}}
    <div class="user-header">
        <div>
            <h1><i class="fas fa-users"></i> Daftar Pengguna</h1>
            <p>Kelola seluruh akun yang terdaftar di sistem prediksi parkir</p>
        </div>
        <div class="stats-badge">
            <i class="fas fa-user-check"></i> Total Pengguna: <strong>{{ $users->total() }}</strong>
        </div>
    </div>

    <div class="user-card">
        {{-- Filter pencarian --}}
        <div class="search-filter">
            <form method="GET" action="{{ route('admin.users') }}" class="search-form">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Cari nama atau email pengguna..." value="{{ request('search') }}" class="search-input">
                </div>
                <button type="submit" class="btn-search">Cari</button>
                @if(request('search'))
                    <a href="{{ route('admin.users') }}" class="btn-reset">Reset</a>
                @endif
            </form>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Pengguna</th>
                        <th>Status Profil</th>
                        <th>Email Terdaftar</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="user-name">
                                @if($user->role === 'admin')
                                    <i class="fas fa-shield-alt" style="color: #DC2626;"></i>
                                @else
                                    <i class="fas fa-user-circle"></i>
                                @endif
                                <strong style="color: #1E293B;">{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge-admin"><i class="fas fa-user-shield"></i> Administrator</span>
                            @elseif(str_ends_with($user->email, '@mahasiswa.pcr.ac.id') || str_ends_with($user->email, '@pcr.ac.id'))
                                <span class="badge-civitas"><i class="fas fa-university"></i> Civitas PCR</span>
                            @else
                                <span class="badge-tamu"><i class="fas fa-globe"></i> Publik / Tamu</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-row">Tidak ada data ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(event, userId, userName) {
        // Mencegah aksi default jika ada
        event.preventDefault();

        const confirmMsg = `Apakah Anda yakin ingin menghapus akun "${userName}"?\n\nCatatan: Riwayat pencarian pengguna ini tidak akan hilang, melainkan diubah menjadi status 'Guest' demi menjaga keutuhan data AI.`;

        if (confirm(confirmMsg)) {
            const form = document.getElementById(`delete-form-${userId}`);
            if (form) {
                form.submit();
            } else {
                console.error("Form tidak ditemukan untuk ID: delete-form-" + userId);
            }
        }
    }
</script>
@endpush
