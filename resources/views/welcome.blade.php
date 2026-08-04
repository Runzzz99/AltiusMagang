<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem HRD') }} | Data Calon Karyawan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{
            --ink:#0f172a;
            --muted:#475569;
            --bg:#f1f5f9;
            --primary:#1e40af;
            --primary-dark:#172554;
            --accent:#f59e0b;
        }
        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--ink);
        }
        h1,h2,h3,.brand{ font-family:'Plus Jakarta Sans',sans-serif; }
        .navbar{ background:var(--primary); box-shadow:0 2px 10px rgba(23,37,84,.25); }
        .navbar .navbar-brand{ color:#fff; font-weight:800; }
        .navbar .navbar-brand small{ display:block; font-size:.7rem; color:#c7d2fe; letter-spacing:.08em; text-transform:uppercase; font-family:'Inter'; font-weight:400; }
        .btn-accent{
            background:var(--accent); border-color:var(--accent); color:#172554; font-weight:700; border-radius:.5rem;
        }
        .btn-accent:hover{ background:#d97706; border-color:#d97706; color:#fff; }
        .hero{
            background:linear-gradient(135deg, #172554 0%, #1e40af 60%, #2563eb 100%);
            color:#fff;
            border-radius:1.25rem;
            padding:3.5rem 2rem;
            overflow:hidden;
        }
        .hero .eyebrow{ color:#f59e0b; font-weight:700; letter-spacing:.1em; text-transform:uppercase; font-size:.8rem; }
        .hero h1{ font-weight:800; }
        .hero .lead{ color:#dbeafe; }
        .feature-card{
            background:#fff; border:1px solid var(--line); border-radius:14px; padding:1.5rem;
        }
        .feature-card .icon{
            width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;
            background:#eef2ff;color:var(--primary);font-size:1.4rem;margin-bottom:1rem;
        }
        .footer{
            color:#64748b; font-size:.85rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">PENDAFTARAN CALON KARYAWAN<small> &middot;Buat akun karyawan baru</small></a>
            <a class="btn btn-accent btn-sm" href="{{ route('calon-karyawan.index') }}">Buka Aplikasi &raquo;</a>
        </div>
    </nav>

    <div class="container py-4">
        {{-- Hero --}}
        <section class="hero text-center text-md-start">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="eyebrow">Sistem Informasi Kepegawaian</div>
                    <h1 class="mt-2">Kelola Data Calon Karyawan<br class="d-none d-md-block"> Dengan Mudah &amp; Rapi</h1>
                    <p class="lead mt-3 mb-4">
                        Catat, cari, dan kelola data calon karyawan dalam satu tempat.
                        Praktis digunakan di laptop maupun HP.
                    </p>
                    <a href="{{ route('calon-karyawan.index') }}" class="btn btn-accent btn-lg px-4 me-2 mb-2">Lihat Daftar Calon Karyawan</a>
                    <a href="{{ route('calon-karyawan.create') }}" class="btn btn-outline-light btn-lg px-4 mb-2">+ Tambah Karyawan Baru</a>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                </div>
            </div>
        </section>

        {{-- Fitur --}}
        <section class="mt-5">
            <div class="text-center mb-4">
                <h2 class="h4 fw-bold">Kenapa Gunakan Sistem Ini?</h2>
                <p class="text-secondary">Dirancang agar mudah dipahami siapa saja.</p>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <h3 class="h6 fw-bold">Data Lengkap &amp; Terstruktur</h3>
                        <p class="small text-secondary mb-0">Data pribadi, pekerjaan, dokumen, rekening, hingga kerabat tercatat rapi dalam satu berkas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <h3 class="h6 fw-bold">Mudah Dicari</h3>
                        <p class="small text-secondary mb-0">Cari calon karyawan berdasarkan nama atau kode, dan saring status aktif maupun nonaktif.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <h3 class="h6 fw-bold">Responsif di Semua Perangkat</h3>
                        <p class="small text-secondary mb-0">Tampilan menyesuaikan layar laptop, tablet, hingga HP agar nyaman digunakan siapa saja.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="footer text-center py-4 mt-4 border-top">
            &copy; {{ date('Y') }} Sistem HRD &middot; Modul Rekrutmen Data Calon Karyawan.
        </footer>
    </div>
</body>
</html>