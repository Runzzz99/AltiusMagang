@extends('layouts.app')
@section('title', 'Daftar Calon Karyawan')

@section('content')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

{{-- Header & Tombol Tambah --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3" data-aos="fade-down" data-aos-duration="600">
    <div>
        <h1 class="h4 mb-1">Daftar Calon Karyawan</h1>
        <div class="text-secondary small">Kelola data calon karyawan di sistem HRD.</div>
    </div>
</div>

{{-- Ringkasan statistik --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-panel p-3 h-100">
            <div class="small text-secondary text-uppercase">Total Kandidat</div>
            <div class="h3 mb-0 fw-bold">{{ $total }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card-panel p-3 h-100">
            <div class="small text-secondary text-uppercase">Aktif</div>
            <div class="h3 mb-0 fw-bold" style="color:var(--primary)">{{ $totalAktif }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4" data-aos="fade-up" data-aos-delay="300">
        <div class="card-panel p-3 h-100">
            <div class="small text-secondary text-uppercase">Nonaktif</div>
            <div class="h3 mb-0 fw-bold text-secondary">{{ $totalNonaktif }}</div>
        </div>
    </div>
</div>

{{-- Pencarian & filter --}}
<form method="GET" action="{{ route('calon-karyawan.index') }}" class="row g-2 mb-3" data-aos="fade-up" data-aos-delay="400">
    <div class="col-12">
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

{{-- Tabel Data --}}
<div class="card-panel p-3" data-aos="fade-up" data-aos-delay="500">
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
                        <td class="text-end"></td>
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