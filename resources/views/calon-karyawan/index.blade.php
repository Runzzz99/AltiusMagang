@extends('layouts.app')
@section('title', 'Daftar Calon Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Calon Karyawan</h1>
    <a href="{{ route('calon-karyawan.create') }}" class="btn btn-teal btn-sm">+ Tambah Calon Karyawan</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card-panel p-3">
    <table class="table align-middle mb-0">
        <thead>
            <tr class="small text-secondary text-uppercase">
                <th>Kode</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td class="fw-semibold">{{ $row->kode }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->divisi ?? '-' }}</td>
                    <td>{{ $row->tgl_masuk?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @if ($row->aktif)
                            <span class="badge bg-success-subtle text-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-end"><a href="#" class="small">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada data calon karyawan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $data->links() }}</div>
@endsection
