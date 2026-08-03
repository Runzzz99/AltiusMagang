@extends('layouts.app')
@section('title', 'Detail ' . $calon->nama)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <a href="{{ route('calon-karyawan.index') }}" class="small text-decoration-none" style="color:var(--primary)">&larr; Kembali ke Daftar</a>
        <h1 class="h4 mb-1 mt-1">Detail Calon Karyawan</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('calon-karyawan.edit', $calon) }}" class="btn btn-primary-soft">Ubah Data</a>
        <form action="{{ route('calon-karyawan.destroy', $calon) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus data \"{{ $calon->nama }}\"? Data yang sudah dihapus tidak bisa dikembalikan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Hapus</button>
        </form>
    </div>
</div>

{{-- Kartu identitas --}}
<div class="card-panel p-3 mb-3">
    <div class="d-flex flex-column flex-sm-row align-items-start gap-3">
        @if ($calon->foto_path)
            <img src="{{ asset('storage/' . $calon->foto_path) }}" alt="Foto {{ $calon->nama }}"
                 style="width:96px;height:96px;object-fit:cover;border-radius:12px;border:1px solid var(--line)">
        @else
            <div class="d-flex align-items-center justify-content-center"
                 style="width:96px;height:96px;border-radius:12px;background:#eef2ff;color:var(--primary);font-size:2rem;font-weight:700">
                {{ strtoupper(mb_substr($calon->nama, 0, 1)) }}
            </div>
        @endif
        <div class="flex-grow-1">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <h2 class="h5 mb-0">{{ $calon->nama }}</h2>
                <span class="kode-badge">{{ $calon->kode }}</span>
                @if ($calon->aktif)
                    <span class="badge" style="background:#d1fae5;color:#065f46">Aktif</span>
                @else
                    <span class="badge text-bg-secondary">Nonaktif</span>
                @endif
            </div>
            <div class="text-secondary small mt-1">
                {{ $calon->panggilan ? 'Panggilan: ' . $calon->panggilan . ' • ' : '' }}
                {{ $calon->divisi ? 'Divisi: ' . $calon->divisi . ' • ' : '' }}
                {{ $calon->pangkat ? 'Jabatan: ' . $calon->pangkat : '' }}
            </div>
        </div>
    </div>
</div>

{{-- Bagian data --}}
@php
    $seksi = [
        'Data Pribadi' => [
            'Tempat / Tgl Lahir' => trim(($calon->tempat_lahir ?? '') . ' ' . ($calon->tgl_lahir?->format('d/m/Y') ?? '')),
            'Jenis Kelamin' => $calon->sex == 'L' ? 'Laki-laki' : ($calon->sex == 'P' ? 'Perempuan' : null),
            'Agama' => $calon->agama,
            'Status Nikah' => $calon->status_nikah,
            'Warga Negara' => $calon->warga_negara,
            'Golongan Darah' => $calon->gol_darah,
            'Tinggi / Berat' => $calon->tinggi_cm || $calon->berat_kg ? trim(($calon->tinggi_cm ?? '-') . ' cm / ' . ($calon->berat_kg ?? '-') . ' kg') : null,
            'No. KTP' => $calon->no_ktp,
            'Alamat KTP' => $calon->alamat_ktp,
            'Kota KTP' => $calon->kota_ktp,
            'No. SIM' => $calon->no_sim,
        ],
        'Alamat & Kontak' => [
            'Alamat Domisili' => $calon->alamat,
            'No. Telepon / HP' => $calon->no_telp,
            'Email' => $calon->email,
            'Status Tempat Tinggal' => $calon->status_tempat_tinggal,
            'Hobi' => $calon->hobby,
        ],
        'Data Pekerjaan' => [
            'Tanggal Masuk' => $calon->tgl_masuk?->format('d/m/Y'),
            'Tanggal Resigned' => $calon->tgl_resigned?->format('d/m/Y'),
            'Alasan Resigned' => $calon->alasan_resigned,
            'Divisi' => $calon->divisi,
            'Pangkat / Jabatan' => $calon->pangkat,
            'Kategori' => $calon->kategori,
            'Sub Kategori' => $calon->sub_kategori,
            'Jalur Pendaftaran' => $calon->jalur_pendaftaran,
            'Awal Cabang' => $calon->awal_cabang,
            'Group of Employee' => $calon->group_of_employee,
            'Awal Group of Employee' => $calon->awal_group_of_employee,
            'NRP' => $calon->nrp,
            'Cost Center' => $calon->cost_center,
            'Posting' => $calon->posting,
            'Cuti / Tahun' => $calon->cuti_per_tahun,
            'Organisasi' => $calon->organisasi,
            'Grup 1 / 2 / 3' => trim(($calon->grup1 ?? '') . ' / ' . ($calon->grup2 ?? '') . ' / ' . ($calon->grup3 ?? '')) ?: null,
        ],
        'Dokumen' => [
            'No. KK' => $calon->no_kk,
            'No. BPJS Kesehatan' => $calon->no_bpjs_kesehatan,
            'No. BPJS Tenaga Kerja' => $calon->no_bpjs_tenaga_kerja,
            'No. Passport' => $calon->no_passport,
            'Passport Expired' => $calon->passport_expired?->format('d/m/Y'),
            'No. Visa' => $calon->no_visa,
        ],
        'Rekening' => [
            'Nama Bank' => $calon->nama_bank,
            'No. Rekening' => $calon->no_rekening,
            'Atas Nama' => $calon->atas_nama_rekening,
            'Tipe Rekening' => $calon->tipe_rekening,
        ],
    ];
@endphp

<div class="row g-3 mb-3">
    @foreach ($seksi as $judul => $fields)
        <div class="col-md-6">
            <div class="card-panel h-100">
                <div class="p-3">
                    <div class="section-title mb-2">{{ $judul }}</div>
                    <dl class="row small mb-0">
                        @forelse ($fields as $label => $value)
                            <dt class="col-5 col-sm-4 text-secondary fw-normal">{{ $label }}</dt>
                            <dd class="col-7 col-sm-8 mb-1">{{ $value ?? '-' }}</dd>
                        @empty
                            <dd class="text-secondary">Tidak ada data.</dd>
                        @endforelse
                    </dl>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Data kerabat --}}
<div class="card-panel p-3">
    <div class="section-title mb-2">Data Kerabat</div>
    <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
            <thead>
                <tr class="small text-secondary text-uppercase">
                    <th>Nama</th>
                    <th>Hubungan</th>
                    <th>No. Telp</th>
                    <th>Pekerjaan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($calon->dataKerabats as $kerabat)
                    <tr>
                        <td>{{ $kerabat->nama }}</td>
                        <td>{{ $kerabat->hubungan ?? '-' }}</td>
                        <td>{{ $kerabat->no_telp ?? '-' }}</td>
                        <td>{{ $kerabat->pekerjaan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-3">Belum ada data kerabat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($calon->keterangan)
    <div class="card-panel p-3 mt-3">
        <div class="section-title mb-2">Keterangan Tambahan</div>
        <p class="mb-0 small">{{ $calon->keterangan }}</p>
    </div>
@endif
@endsection