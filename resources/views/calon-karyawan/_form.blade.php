{{--
    Form reusable untuk Tambah (calon = null) & Edit (calon = object).
    Variabel yang tersedia: $calon (nullable), $kodeBerikutnya, $kodeField.
--}}
@php
    $isEdit = isset($calon) && !is_null($calon);
    $formAction = $isEdit ? route('calon-karyawan.update', $calon) : route('calon-karyawan.store');
    $namaForm = $isEdit ? 'Simpan Perubahan' : 'Simpan Data Calon Karyawan';
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
    <div>
        <h1 class="h4 mb-1">{{ $isEdit ? 'Ubah Data Calon Karyawan' : 'Input Data Calon Karyawan' }}</h1>
        <div class="text-secondary small">Lengkapi data berikut untuk {{ $isEdit ? 'memperbarui' : 'mendaftarkan' }} kandidat ke sistem HRD.</div>
    </div>
    <span class="kode-badge">Kode: {{ $isEdit ? $calon->kode : $kodeBerikutnya }}</span>
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
    <input type="hidden" name="kode" value="{{ old('kode', $isEdit ? $calon->kode : $kodeBerikutnya) }}">

    <div class="card-panel">
        <nav class="nav-steps" id="stepNav">
            <button type="button" class="step-btn active" data-target="pane-pribadi"><span class="step-label"><span class="num">1</span>Data Pribadi</span></button>
            <button type="button" class="step-btn" data-target="pane-kontak"><span class="step-label"><span class="num">2</span>Alamat &amp; Kontak</span></button>
            <button type="button" class="step-btn" data-target="pane-kerja"><span class="step-label"><span class="num">3</span>Data Pekerjaan</span></button>
            <button type="button" class="step-btn" data-target="pane-dokumen"><span class="step-label"><span class="num">4</span>Dokumen &amp; Kerabat</span></button>
            <button type="button" class="step-btn" data-target="pane-rekening"><span class="step-label"><span class="num">5</span>Rekening &amp; Foto</span></button>
        </nav>

        {{-- 1. DATA PRIBADI --}}
        <div class="section-pane active" id="pane-pribadi">
            <div class="section-title">Identitas Diri</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $calon->nama ?? '') }}" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" placeholder="Contoh: Budi Santoso" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Panggilan</label>
                    <input type="text" name="panggilan" value="{{ old('panggilan', $calon->panggilan ?? '') }}" class="form-control" placeholder="Contoh: Budi">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $calon->tempat_lahir ?? '') }}" class="form-control" placeholder="Contoh: Jakarta">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $calon?->tgl_lahir?->format('Y-m-d') ?? '') }}" class="form-control">
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
                    <input type="text" name="no_ktp" value="{{ old('no_ktp', $calon->no_ktp ?? '') }}" class="form-control" placeholder="Contoh: 3174xxxxxxxxxxxx">
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
                <div class="col-md-4">
                    <label class="form-label">No. Telepon / HP</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp', $calon->no_telp ?? '') }}" class="form-control" placeholder="Contoh: 0812xxxx">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $calon->email ?? '') }}" class="form-control" placeholder="Contoh: budi@email.com">
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
                    <input type="date" name="tgl_masuk" value="{{ old('tgl_masuk', $calon?->tgl_masuk?->format('Y-m-d') ?? '') }}" class="form-control">
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
                    <input type="text" name="no_bpjs_kesehatan" value="{{ old('no_bpjs_kesehatan', $calon->no_bpjs_kesehatan ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. BPJS Tenaga Kerja</label>
                    <input type="text" name="no_bpjs_tenaga_kerja" value="{{ old('no_bpjs_tenaga_kerja', $calon->no_bpjs_tenaga_kerja ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Passport</label>
                    <input type="text" name="no_passport" value="{{ old('no_passport', $calon->no_passport ?? '') }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Passport Expired</label>
                    <input type="date" name="passport_expired" value="{{ old('passport_expired', $calon?->passport_expired?->format('Y-m-d') ?? '') }}" class="form-control">
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
                            <div class="col-12 col-md-3">
                                <label class="form-label">Hubungan</label>
                                <select name="kerabat_hubungan[]" class="form-select">
                                    <option value="">Pilih</option>
                                    @foreach (['Suami/Istri','Anak','Orang Tua','Saudara Kandung'] as $opt)
                                        <option {{ $kerabat->hubungan == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-2">
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
                    <div class="col-12 col-md-3">
                        <label class="form-label">Hubungan</label>
                        <select name="kerabat_hubungan[]" class="form-select">
                            <option value="">Pilih</option>
                            <option>Suami/Istri</option><option>Anak</option><option>Orang Tua</option><option>Saudara Kandung</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
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
                    <input type="text" name="nama_bank" value="{{ old('nama_bank', $calon->nama_bank ?? '') }}" class="form-control" placeholder="Contoh: BCA">
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. Rekening</label>
                    <input type="text" name="no_rekening" value="{{ old('no_rekening', $calon->no_rekening ?? '') }}" class="form-control">
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
                    <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Masukkan password">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Foto Kandidat</label>
                    <label class="photo-drop d-block" for="fotoInput">
                        {{-- Tampilkan foto lama pada mode edit --}}
                        @if ($isEdit && $calon->foto_path)
                            <img id="fotoPreview" src="{{ asset('storage/' . $calon->foto_path) }}" alt="Foto {{ $calon->nama }}" style="display:inline-block">
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
</form>

<script>
    // Navigasi antar tab/step
    const steps = document.querySelectorAll('.step-btn');
    const panes = document.querySelectorAll('.section-pane');
    function goTo(targetId){
        panes.forEach(p => p.classList.toggle('active', p.id === targetId));
        steps.forEach(s => s.classList.toggle('active', s.dataset.target === targetId));
        window.scrollTo({top: document.querySelector('.card-panel').offsetTop - 20, behavior:'smooth'});
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