# UI/UX Audit AltiusMagang

Scope file yang diperiksa:
- `resources/views/welcome.blade.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/calon-karyawan/index.blade.php`
- `resources/views/calon-karyawan/show.blade.php`
- `resources/views/calon-karyawan/_form.blade.php`
- `resources/views/calon-karyawan/create.blade.php`
- `resources/views/calon-karyawan/edit.blade.php`
- `resources/css/app.css`
- `resources/js/app.js`
- `public/img/*`
- `public/video/*`

## Ringkasan desain sekarang
Website sudah punya struktur dasar HRD: landing, daftar kandidat, detail, tambah/edit wizard, filter, tabel, alert, modal foto. Masalah utama bukan warna, tapi alur dan rasa produk: terlalu seperti template Bootstrap/SaaS, banyak animasi/gradient, beberapa CTA duplikat, form terlalu padat, aksi tabel icon-only, detail data terlalu ramai dengan nilai kosong, dan empty/error state belum cukup membantu pengguna awam.

## P0 — sangat penting untuk usability

### Form: `No. HP` muncul dua kali
- File: `_form.blade.php`
- Masalah UX: field `no_hp` muncul di step Data Pribadi dan step Alamat & Kontak. Pengguna bisa bingung harus isi yang mana. Karena name sama, input yang terakhir berisiko menimpa nilai sebelumnya saat submit.
- Solusi: simpan `No. HP` hanya di step Alamat & Kontak.

### Tabel: aksi icon-only
- File: `index.blade.php`
- Masalah UX: tombol Detail/Ubah/Hapus hanya ikon. Pengguna awam harus menebak fungsi. `title` tidak cukup, terutama mobile.
- Solusi: tampilkan label teks kecil di tombol aksi.

### Detail: tidak ada CTA edit yang jelas
- File: `show.blade.php`
- Masalah UX: halaman detail harus punya aksi utama `Ubah Data`, tetapi header hanya punya tombol kembali duplikat.
- Solusi: header detail berisi breadcrumb kecil, tombol `Ubah Data`, dan tombol kembali sekunder.

## P1 — penting

### Navigasi aktif kurang jelas
- File: `layouts/app.blade.php`, `welcome.blade.php`
- Masalah UX: state aktif navbar lemah. Pengguna tidak langsung tahu posisi halaman.
- Solusi: nav active pakai background/underline jelas dan warna kontras.

### Detail terlalu padat dan banyak nilai kosong
- File: `show.blade.php`
- Masalah UX: banyak field `-` membuat staf HR susah scan informasi penting.
- Solusi: tampilkan nilai kosong dengan visual muted, pertajam kartu identitas, dan empty state per bagian.

### Empty state daftar terlalu pasif
- File: `index.blade.php`
- Masalah UX: saat kosong, hanya teks di dalam tabel. Tidak ada tindakan berikutnya.
- Solusi: empty state dengan pesan, konteks pencarian, tombol reset atau tambah kandidat.

### Motion berlebihan
- File: `layouts/app.blade.php`, `welcome.blade.php`, `index.blade.php`
- Masalah UX: AOS + blur/scale form terasa dekoratif, bukan produktif. HRD app harus cepat dan stabil.
- Solusi: kurangi animasi, hormati `prefers-reduced-motion`.

## P2 — improvement

### Landing terlalu marketing/SaaS
- File: `welcome.blade.php`
- Masalah UX: metric kandidat muncul berulang (hero chips, mockup dashboard, stat cards). Terasa template, bukan aplikasi HRD internal.
- Solusi: kurangi duplikasi, arahkan landing ke workflow HRD: daftar kandidat, input data, review.

### Filter daftar kurang hierarkis
- File: `index.blade.php`
- Masalah UX: input search dan tombol terlihat seperti form biasa, bukan toolbar data.
- Solusi: bungkus filter dalam panel toolbar, beri label jelas, tombol reset konsisten.

### Tabel perlu readability lebih baik
- File: `index.blade.php`
- Masalah UX: baris data sulit discan, khususnya desktop lebar.
- Solusi: zebra row, hover state, badge status konsisten, kode pakai mono.

### Mobile action area
- File: `index.blade.php`, `_form.blade.php`
- Masalah UX: tombol kecil banyak berdempetan.
- Solusi: minimum touch target, tombol full width hanya saat mobile, label teks tetap muncul.

## P3 — cosmetic

### Gradient dan shadow terlalu banyak
- Masalah UX: terlihat seperti Bootstrap template dimodifikasi.
- Solusi: corporate flat surface, sedikit shadow, warna netral + biru gelap.

### Brand naming terlalu panjang di navbar
- Masalah UX: `PENDAFTARAN CALON KARYAWAN` kurang terasa seperti nama aplikasi.
- Solusi: gunakan `AltiusMagang` + sublabel `HRD Candidate System`.

## Prioritas implementasi
1. Hapus duplikasi `No. HP`.
2. Perjelas tombol aksi tabel.
3. Perbaiki header detail dan tambah CTA edit.
4. Perbaiki empty state daftar/detail.
5. Rapikan token visual global: navbar, card, button, table, focus, alert.
6. Kurangi motion berlebihan di form.

## Batasan yang dijaga
- Tidak mengubah database.
- Tidak mengubah migration.
- Tidak mengubah struktur SQL Server.
- Tidak mengubah nama kolom.
- Tidak mengubah model database.
- Tidak mengubah koneksi database.
- Perubahan fokus Blade/CSS/JS UI saja.
