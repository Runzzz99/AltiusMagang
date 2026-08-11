{{--
    Form reusable untuk Tambah (calon = null) & Edit (calon = object).
    Variabel yang tersedia: $calon (nullable), $kodeBerikutnya, $kodeField.
--}}
@php
    $isEdit = isset($calon) && !is_null($calon);
    $formAction = $isEdit ? route('calon-karyawan.update', $calon) : route('calon-karyawan.store');
    $namaForm = $isEdit ? 'Simpan Perubahan' : 'Simpan Data Calon Karyawan';

    // Label langkah untuk stepper sidebar & breadcrumb
    $steps = [
        ['id' => 'pane-pribadi',  'label' => 'Data Pribadi'],
        ['id' => 'pane-kontak',   'label' => 'Alamat & Kontak'],
        ['id' => 'pane-kerja',    'label' => 'Data Pekerjaan'],
        ['id' => 'pane-dokumen',  'label' => 'Dokumen & Kerabat'],
        ['id' => 'pane-rekening', 'label' => 'Rekening & Foto'],
    ];
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
    <div>
        <h1 class="h4 mb-1">{{ $isEdit ? 'Ubah Data Calon Karyawan' : 'Input Data Calon Karyawan' }}</h1>
        <div class="text-secondary small">Lengkapi data berikut untuk {{ $isEdit ? 'memperbarui' : 'mendaftarkan' }} kandidat ke sistem HRD.</div>
    </div>
    <span class="kode-badge">Kode: {{ $isEdit ? $calon->kode : ($kodeBerikutnya ?? '-') }}</span>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Periksa kembali isian berikut:</div>
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="candidateForm">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif
    <input type="hidden" name="kode" value="{{ old('kode', $isEdit ? $calon->kode : ($kodeBerikutnya ?? '')) }}">

    {{-- Progress bar atas --}}
    <div class="wizard-progress"><div class="progress-fill" id="wizFill" style="width:20%"></div></div>

    {{-- Breadcrumb langkah (fallback mobile) --}}
    <div class="wizard-stepbar">
        <span class="w-indicator"><span class="step-num" id="stepCounter">1</span></span>
        <span class="w-title" id="stepTitle">Data Pribadi</span>
        <span class="text-secondary small ms-auto">Langkah <span id="wizCount">1</span>/5</span>
    </div>

    <div class="row g-3">
        <div class="col-lg-4 col-xl-3">
            <div class="card-panel p-2">
                <aside class="step-sidebar" id="stepNav" aria-label="Langkah pengisian">
                    @foreach ($steps as $i => $st)
                        <button type="button" class="step-btn {{ $i === 0 ? 'active' : '' }}" data-target="{{ $st['id'] }}" data-label="{{ $st['label'] }}">
                            <span class="step-num">{{ $i + 1 }}</span>
                            <span>{{ $st['label'] }}</span>
                        </button>
                    @endforeach
                </aside>
            </div>
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="card-panel">

        {{-- 1. DATA PRIBADI --}}
        <div class="section-pane active" id="pane-pribadi">
            <div class="section-title">Identitas Diri</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input type="text" name="nama" value="{{ old('nama', $calon->nama ?? '') }}" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Panggilan</label>
                    <input type="text" name="panggilan" value="{{ old('panggilan', $calon->panggilan ?? '') }}" class="form-control" placeholder="Contoh: Budi">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $calon->tempat_lahir ?? '') }}" class="form-control" placeholder="Contoh: Jakarta">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $calon?->tgl_lahir?->format('Y-m-d') ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="sex" class="form-select">
                        <option value="">Pilih</option>
                        <option value="L" {{ old('sex', $calon->sex ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('sex', $calon->sex ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Agama</label>
                    <input type="text" name="agama" value="{{ old('agama', $calon->agama ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Nikah</label>
                    <select name="status_nikah" class="form-select">
                        <option value="">Pilih</option>
                        @foreach (['Belum Menikah','Menikah','Cerai'] as $opt)
                            <option {{ old('status_nikah', $calon->status_nikah ?? '') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Warga Negara</label>
                    <input type="text" name="warga_negara" value="{{ old('warga_negara', $calon->warga_negara ?? 'Indonesia') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Golongan Darah</label>
                    <select name="gol_darah" class="form-select">
                        <option value="">Pilih</option>
                        @foreach (['A','B','AB','O'] as $opt)
                            <option {{ old('gol_darah', $calon->gol_darah ?? '') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tinggi Badan (cm)</label>
                    <input type="number" name="tinggi_cm" value="{{ old('tinggi_cm', $calon->tinggi_cm ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Berat Badan (kg)</label>
                    <input type="number" name="berat_kg" value="{{ old('berat_kg', $calon->berat_kg ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. KTP</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><circle cx="8" cy="10" r="2"/><path d="M12 14h6M12 17h6M4.5 16a3.5 3.5 0 0 1 7 0"/></svg>
                        </span>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp', $calon->no_ktp ?? '') }}" class="form-control" placeholder="Contoh: 3174xxxxxxxxxxxx">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $calon->no_hp ?? '') }}" class="form-control" placeholder="Contoh: 0812xxxx">
                    </div>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Alamat KTP</label>
                    <input type="text" name="alamat_ktp" value="{{ old('alamat_ktp', $calon->alamat_ktp ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kota KTP</label>
                    <input type="text" name="kota_ktp" value="{{ old('kota_ktp', $calon->kota_ktp ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. SIM</label>
                    <input type="text" name="no_sim" value="{{ old('no_sim', $calon->no_sim ?? '') }}" class="form-control">
                </div>
            </div>
            <div class="text-end mt-4">
                <button type="button" class="btn btn-primary-soft next-step w-100 w-md-auto">Lanjut: Alamat &amp; Kontak &rarr;</button>
            </div>
        </div>

        {{-- 2. ALAMAT & KONTAK --}}
        <div class="section-pane" id="pane-kontak">
            <div class="section-title">Alamat &amp; Kontak</div>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Alamat Domisili</label>
                    <textarea name="alamat" rows="2" class="form-control" placeholder="Alamat tinggal sekarang">{{ old('alamat', $calon->alamat ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </span>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $calon->no_hp ?? '') }}" class="form-control" placeholder="Contoh: 0812xxxx">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email', $calon->email ?? '') }}" class="form-control" placeholder="Contoh: budi@email.com">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Tempat Tinggal</label>
                    <select name="status_tempat_tinggal" class="form-select">
                        <option value="">Pilih</option>
                        @foreach (['Milik Sendiri','Kontrak/Sewa','Ikut Orang Tua','Kos/Asrama'] as $opt)
                            <option {{ old('status_tempat_tinggal', $calon->status_tempat_tinggal ?? '') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hobi</label>
                    <input type="text" name="hobby" value="{{ old('hobby', $calon->hobby ?? '') }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="2" class="form-control" placeholder="Catatan tambahan bila ada">{{ old('keterangan', $calon->keterangan ?? '') }}</textarea>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary prev-step order-2 order-sm-1">&larr; Kembali</button>
                <button type="button" class="btn btn-primary-soft next-step order-1 order-sm-2">Lanjut: Data Pekerjaan &rarr;</button>
            </div>
        </div>

        {{-- 3. DATA PEKERJAAN --}}
        <div class="section-pane" id="pane-kerja">
            <div class="section-title">Data Pekerjaan &amp; Penempatan</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Masuk</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', $calon?->tgl_masuk?->format('Y-m-d') ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Awal Cabang</label>
                    <input type="text" name="awal_cabang" value="{{ old('awal_cabang', $calon->awal_cabang ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Group of Employee</label>
                    <input type="text" name="group_of_employee" value="{{ old('group_of_employee', $calon->group_of_employee ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Awal Group of Employee</label>
                    <input type="text" name="awal_group_of_employee" value="{{ old('awal_group_of_employee', $calon->awal_group_of_employee ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Divisi</label>
                    <input type="text" name="divisi" value="{{ old('divisi', $calon->divisi ?? '') }}" class="form-control" placeholder="Contoh: Marketing">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pangkat / Jabatan</label>
                    <input type="text" name="pangkat" value="{{ old('pangkat', $calon->pangkat ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', $calon->kategori ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Sub Kategori</label>
                    <input type="text" name="sub_kategori" value="{{ old('sub_kategori', $calon->sub_kategori ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jalur Pendaftaran</label>
                    <input type="text" name="jalur_pendaftaran" value="{{ old('jalur_pendaftaran', $calon->jalur_pendaftaran ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">NRP</label>
                    <input type="text" name="nrp" value="{{ old('nrp', $calon->nrp ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cost Center</label>
                    <input type="text" name="cost_center" value="{{ old('cost_center', $calon->cost_center ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Posting</label>
                    <input type="text" name="posting" value="{{ old('posting', $calon->posting ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cuti / Tahun</label>
                    <input type="number" name="cuti_per_tahun" value="{{ old('cuti_per_tahun', $calon->cuti_per_tahun ?? 12) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Organisasi</label>
                    <input type="text" name="organisasi" value="{{ old('organisasi', $calon->organisasi ?? '') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Grup 1</label>
                    <input type="text" name="grup1" value="{{ old('grup1', $calon->grup1 ?? '') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Grup 2</label>
                    <input type="text" name="grup2" value="{{ old('grup2', $calon->grup2 ?? '') }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Grup 3</label>
                    <input type="text" name="grup3" value="{{ old('grup3', $calon->grup3 ?? '') }}" class="form-control">
                </div>
                <div class="col-12 form-check mt-2">
                    <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktifCheck"
                           {{ old('aktif', $calon?->aktif ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="aktifCheck">Status aktif sebagai kandidat</label>
                </div>
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary prev-step order-2 order-sm-1">&larr; Kembali</button>
                <button type="button" class="btn btn-primary-soft next-step order-1 order-sm-2">Lanjut: Dokumen &amp; Kerabat &rarr;</button>
            </div>
        </div>

        {{-- 4. DOKUMEN & KERABAT --}}
        <div class="section-pane" id="pane-dokumen">
            <div class="section-title">Dokumen Identitas</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">No. KK</label>
                    <input type="text" name="no_kk" value="{{ old('no_kk', $calon->no_kk ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. BPJS Kesehatan</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </span>
                        <input type="text" name="no_bpjs_kesehatan" value="{{ old('no_bpjs_kesehatan', $calon->no_bpjs_kesehatan ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. BPJS Tenaga Kerja</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z"/><path d="M8.5 12l2.5 2.5 5-5"/></svg>
                        </span>
                        <input type="text" name="no_bpjs_tenaga_kerja" value="{{ old('no_bpjs_tenaga_kerja', $calon->no_bpjs_tenaga_kerja ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Passport</label>
                    <input type="text" name="no_passport" value="{{ old('no_passport', $calon->no_passport ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Passport Expired</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </span>
                        <input type="date" name="passport_expired" value="{{ old('passport_expired', $calon?->passport_expired?->format('Y-m-d') ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Visa</label>
                    <input type="text" name="no_visa" value="{{ old('no_visa', $calon->no_visa ?? '') }}" class="form-control">
                </div>
            </div>

            <div class="section-title d-flex justify-content-between align-items-center">
                <span>Data Kerabat</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="addKerabat">+ Tambah Kerabat</button>
            </div>
            <div id="kerabatWrapper">
                @if ($isEdit)
                    @foreach ($calon->dataKerabats as $kerabat)
                        <div class="kerabat-row row g-2 align-items-end">
                            <div class="col-12 col-md-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="kerabat_nama[]" value="{{ $kerabat->nama }}" class="form-control">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Hubungan</label>
                                <select name="kerabat_hubungan[]" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach (['Suami/Istri','Anak','Orang Tua','Saudara Kandung'] as $opt)
                                        <option {{ $kerabat->hubungan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label">No. Telp</label>
                                <input type="text" name="kerabat_telp[]" value="{{ $kerabat->no_telp }}" class="form-control">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="kerabat_pekerjaan[]" value="{{ $kerabat->pekerjaan }}" class="form-control">
                            </div>
                            <div class="col-12 col-md-1">
                                <button type="button" class="btn btn-link text-danger remove-kerabat">Hapus</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <template id="kerabatTemplate">
                <div class="kerabat-row row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="kerabat_nama[]" class="form-control">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Hubungan</label>
                        <select name="kerabat_hubungan[]" class="form-select">
                            <option value="">Pilih</option>
                            <option>Suami/Istri</option><option>Anak</option><option>Orang Tua</option><option>Saudara Kandung</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">No. Telp</label>
                        <input type="text" name="kerabat_telp[]" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="kerabat_pekerjaan[]" class="form-control">
                    </div>
                    <div class="col-12 col-md-1">
                        <button type="button" class="btn btn-link text-danger remove-kerabat">Hapus</button>
                    </div>
                </div>
            </template>

            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary prev-step order-2 order-sm-1">&larr; Kembali</button>
                <button type="button" class="btn btn-primary-soft next-step order-1 order-sm-2">Lanjut: Rekening &amp; Foto &rarr;</button>
            </div>
        </div>

        {{-- 5. REKENING & FOTO --}}
        <div class="section-pane" id="pane-rekening">
            <div class="section-title">Informasi Rekening</div>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Nama Bank</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="6" width="18" height="12" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="7" y1="15" x2="10" y2="15"/></svg>
                        </span>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank', $calon->nama_bank ?? '') }}" class="form-control" placeholder="Contoh: BCA">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. Rekening</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 15h.01M11 15h2M15 15h.01M7 11h10"/></svg>
                        </span>
                        <input type="text" name="no_rekening" value="{{ old('no_rekening', $calon->no_rekening ?? '') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Atas Nama</label>
                    <input type="text" name="atas_nama_rekening" value="{{ old('atas_nama_rekening', $calon->atas_nama_rekening ?? '') }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tipe Rekening</label>
                    <input type="text" name="tipe_rekening" value="{{ old('tipe_rekening', $calon->tipe_rekening ?? '') }}" class="form-control">
                </div>
            </div>

            <div class="section-title">Akun &amp; Foto</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Password Akun (@if($isEdit) biarkan kosong agar tidak berubah @else opsional @endif)</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Masukkan password">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Foto Kandidat</label>
                    <label class="photo-drop d-block" for="fotoInput">
                        {{-- Tampilkan foto lama pada mode edit --}}
                        @if ($isEdit && $calon->foto_path)
                            <img id="fotoPreview" src="{{ route('calon-karyawan.foto', $calon) }}" alt="Foto {{ $calon->nama }}" style="display:inline-block">
                            <div id="fotoPlaceholder" style="display:none">Klik untuk ganti foto (JPG/PNG, maks 2MB)</div>
                        @else
                            <img id="fotoPreview" alt="Preview foto">
                            <div id="fotoPlaceholder">Klik untuk unggah foto (JPG/PNG, maks 2MB)</div>
                        @endif
                    </label>
                    <input type="file" id="fotoInput" name="foto" accept="image/*" class="d-none">
                </div>
            </div>

            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                <a href="{{ route('calon-karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary-soft">{{ $namaForm }}</button>
            </div>
        </div>
        </div>
    </div>
</form>

<script>
    // Navigasi antar tab/step
    const steps = document.querySelectorAll('.step-btn');
    const panes = document.querySelectorAll('.section-pane');
    const stepTitle = document.getElementById('stepTitle');
    const stepCounter = document.getElementById('stepCounter');
    const wizCount = document.getElementById('wizCount');
    const wizFill = document.getElementById('wizFill');
    const totalSteps = panes.length;

    let isTransitioning = false;

    function goTo(targetId){
        if (isTransitioning) return;
        const idx = Array.from(panes).findIndex(p => p.id === targetId);
        if (idx === -1) return;

        const current = document.querySelector('.section-pane.active');
        const target  = document.getElementById(targetId);
        if (!current || !target || current === target) return;

        isTransitioning = true;

        // Update sidebar & progress immediately
        steps.forEach((s, i) => s.classList.toggle('active', i === idx));
        if (stepTitle) stepTitle.textContent = steps[idx].dataset.label || steps[idx].textContent.trim();
        if (stepCounter) stepCounter.textContent = idx + 1;
        if (wizCount) wizCount.textContent = idx + 1;
        if (wizFill) wizFill.style.width = ((idx + 1) / totalSteps) * 100 + '%';

        // Fade out current pane
        current.classList.remove('active');
        current.classList.add('pane-exit');

        current.addEventListener('animationend', function handler(){
            current.removeEventListener('animationend', handler);
            current.classList.remove('pane-exit');
            current.style.display = 'none';

            // Show target pane with entrance animation
            target.style.display = '';
            target.classList.add('active');

            const panel = document.querySelector('.card-panel');
            window.scrollTo({top: (panel ? panel.offsetTop : 0) - 20, behavior:'smooth'});

            setTimeout(()=>{ isTransitioning = false; }, 500);
        });
    }
    steps.forEach(btn => btn.addEventListener('click', () => goTo(btn.dataset.target)));
    document.querySelectorAll('.next-step').forEach(btn => btn.addEventListener('click', () => {
        const current = document.querySelector('.section-pane.active');
        const idx = Array.from(panes).indexOf(current);
        if (panes[idx + 1]) goTo(panes[idx + 1].id);
    }));
    document.querySelectorAll('.prev-step').forEach(btn => btn.addEventListener('click', () => {
        const current = document.querySelector('.section-pane.active');
        const idx = Array.from(panes).indexOf(current);
        if (panes[idx - 1]) goTo(panes[idx - 1].id);
    }));

    // Baris Data Kerabat dinamis (event delegation agar baris lama & baru diproses sama)
    const kerabatWrapper = document.getElementById('kerabatWrapper');
    const kerabatTemplate = document.getElementById('kerabatTemplate');

    kerabatWrapper.addEventListener('click', function(e){
        if (e.target.classList.contains('remove-kerabat')) {
            e.target.closest('.kerabat-row').remove();
        }
    });

    document.getElementById('addKerabat').addEventListener('click', function(){
        const clone = kerabatTemplate.content.cloneNode(true);
        kerabatWrapper.appendChild(clone);
    });

    // Tambahkan satu baris kosong otomatis pada mode tambah
    @if (!$isEdit)
        document.getElementById('addKerabat').click();
    @endif

    // Preview foto
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreview = document.getElementById('fotoPreview');
    const fotoPlaceholder = document.getElementById('fotoPlaceholder');
    fotoInput.addEventListener('change', function(){
        if (this.files && this.files[0]) {
            fotoPreview.src = URL.createObjectURL(this.files[0]);
            fotoPreview.style.display = 'inline-block';
            fotoPlaceholder.style.display = 'none';
        }
    });
</script>