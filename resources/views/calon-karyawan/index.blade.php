@extends('layouts.app')
@section('title', 'Daftar Calon Karyawan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h4 mb-1">Daftar Calon Karyawan</h1>
        <div class="text-secondary small">Kelola data calon karyawan di sistem HRD.</div>
    </div>
    <a href="{{ route('calon-karyawan.create') }}" class="btn btn-primary-soft">+ Tambah Calon Karyawan</a>
</div>

{{-- Ringkasan statistik --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-4">
        <div class="card-panel p-3 h-100">
            <div class="small text-secondary text-uppercase">Total Kandidat</div>
            <div class="h3 mb-0 fw-bold">{{ $total }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card-panel p-3 h-100">
            <div class="small text-secondary text-uppercase">Aktif</div>
            <div class="h3 mb-0 fw-bold" style="color:var(--primary)">{{ $totalAktif }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card-panel p-3 h-100">
            <div class="small text-secondary text-uppercase">Nonaktif</div>
            <div class="h3 mb-0 fw-bold text-secondary">{{ $totalNonaktif }}</div>
        </div>
    </div>
</div>

{{-- Pencarian & filter --}}
<form method="GET" action="{{ route('calon-karyawan.index') }}" class="row g-2 mb-3">
    <div class="col-12 col-md-5">
        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
               placeholder="Cari nama atau kode kandidat...">
    </div>
    <div class="col-6 col-md-3">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
    <div class="col-6 col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary-soft flex-fill">Cari</button>
        @if (request('q') || request('status'))
            <a href="{{ route('calon-karyawan.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </div>
</form>

<div class="card-panel p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr class="small text-secondary text-uppercase">
                    <th>Kode</th>
                    <th>Nama</th>
                    <th class="d-none d-md-table-cell">Divisi</th>
                    <th class="d-none d-md-table-cell">Tanggal Masuk</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $row)
                    <tr>
                        <td class="fw-semibold">{{ $row->kode }}</td>
                        <td>
                            <a href="{{ route('calon-karyawan.show', $row) }}" class="fw-semibold text-decoration-none" style="color:var(--primary)">
                                {{ $row->nama }}
                            </a>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $row->divisi ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $row->tgl_masuk?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if ($row->aktif)
                                <span class="badge" style="background:#d1fae5;color:#065f46">Aktif</span>
                            @else
                                <span class="badge text-bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                                <a href="{{ route('calon-karyawan.show', $row) }}" class="btn btn-outline-primary" title="Lihat detail">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('calon-karyawan.edit', $row) }}" class="btn btn-outline-warning" title="Ubah data">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                </a>
                                <form action="{{ route('calon-karyawan.destroy', $row) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus data \"{{ $row->nama }}\"? Data yang sudah dihapus tidak bisa dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus data">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">
                            @if (request('q') || request('status'))
                                Tidak ada data yang cocok dengan pencarian.
                            @else
                                Belum ada data calon karyawan. Klik tombol <strong>Tambah Calon Karyawan</strong> untuk memulai.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($data->hasPages())
    <div class="mt-3">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
