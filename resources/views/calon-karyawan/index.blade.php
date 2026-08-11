@extends('layouts.app')
@section('title', 'Daftar Calon Karyawan')

@section('content')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

{{-- Header & Tombol Tambah --}}
<div class="page-head">
    <div>
        <h1>Daftar Calon Karyawan</h1>
        <p class="page-sub">Kelola data calon karyawan di sistem HRD.</p>
    </div>
    <a href="{{ route('calon-karyawan.create') }}" class="btn btn-primary-soft">
        + Tambah Calon Karyawan
    </a>
</div>

{{-- Ringkasan statistik --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-4">
        <div class="metric-card">
            <div class="metric-label">Total Kandidat</div>
            <div class="metric-value">{{ number_format($total) }}</div>
            <div class="metric-sub">Tercatat di sistem</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="metric-card">
            <div class="metric-label">Aktif</div>
            <div class="metric-value" style="color:var(--primary)">{{ number_format($totalAktif) }}</div>
            <div class="metric-sub">Dalam proses rekrutmen</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="metric-card">
            <div class="metric-label">Nonaktif</div>
            <div class="metric-value">{{ number_format($totalNonaktif) }}</div>
            <div class="metric-sub">Sudah tidak aktif</div>
        </div>
    </div>
</div>

{{-- Pencarian & filter --}}
<form method="GET" action="{{ route('calon-karyawan.index') }}" class="data-toolbar" role="search">
    <div class="search-wrap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" class="form-control"
               placeholder="Cari nama atau kode kandidat..." aria-label="Cari nama atau kode kandidat">
    </div>
    <select name="status" class="form-select" style="width:auto;min-width:140px" aria-label="Filter status">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    <button type="submit" class="btn btn-primary-soft px-3">Cari</button>
    @if (request('q') || request('status'))
        <a href="{{ route('calon-karyawan.index') }}" class="btn btn-outline-secondary">Reset</a>
    @endif
</form>

{{-- Tabel Data --}}
<div class="card-panel p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
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
                        <td class="fw-semibold font-monospace">{{ $row->kode }}</td>
                        <td>
                            <a href="{{ route('calon-karyawan.show', $row) }}" class="fw-semibold text-decoration-none" style="color:var(--primary)">
                                {{ $row->nama }}
                            </a>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $row->divisi ?? '-' }}</td>
                        <td class="d-none d-md-table-cell">{{ $row->tgl_masuk?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            <span class="status-badge {{ $row->aktif ? 'is-aktif' : 'is-nonaktif' }}">
                                {{ $row->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1 table-sm-actions">
                                <a href="{{ route('calon-karyawan.show', $row) }}" class="btn btn-sm btn-outline-primary btn-action" title="Detail">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <span>Detail</span>
                                </a>
                                <a href="{{ route('calon-karyawan.edit', $row) }}" class="btn btn-sm btn-outline-secondary btn-action" title="Ubah">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    <span>Ubah</span>
                                </a>
                                <form action="{{ route('calon-karyawan.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Hapus">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                </div>
                                @if (request('q') || request('status'))
                                    <h3>Data tidak ditemukan</h3>
                                    <p>Ubah kata kunci atau reset filter untuk melihat semua kandidat.</p>
                                    <a href="{{ route('calon-karyawan.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                                @else
                                    <h3>Belum ada calon karyawan</h3>
                                    <p>Mulai pencatatan kandidat pertama untuk proses HRD.</p>
                                    <a href="{{ route('calon-karyawan.create') }}" class="btn btn-primary-soft btn-sm">+ Tambah Calon Karyawan</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($data->hasPages())
    <div class="mt-3" data-aos="fade-up">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
@endif

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        AOS.init({
            once: true,            // Animasi hanya berjalan 1 kali saat di-scroll
            duration: 700,         // Durasi animasi (milidetik)
            easing: 'ease-out-cubic',
            offset: 40             // Jarak trigger sebelum elemen terlihat
        });
    });
</script>
@endsection