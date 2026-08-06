<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem HRD') }} | Data Calon Karyawan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root{
            --ink:#0f172a;
            --muted:#52607a;
            --bg:#f1f5f9;
            --primary:#1e40af;
            --primary-dark:#172554;
            --blue:#4A90E2;
            --blue-deep:#1A4B8C;
            --accent:#f59e0b;
            --accent-dark:#d97706;
        }
        html{
            /* Scroll yang lebih halus di semua perangkat */
            scroll-behavior:smooth;
            /* Beri jarak agar konten tidak tertutup navbar sticky saat anchor/scroll */
            scroll-padding-top:84px;
        }
        @media (prefers-reduced-motion: reduce){
            html{ scroll-behavior:auto; }
        }
        *{ -webkit-font-smoothing:antialiased; text-rendering:optimizeLegibility; }
        body{
            font-family:'Inter',sans-serif;
            background:var(--bg);
            color:var(--ink);
        }
        h1,h2,h3,.brand,.display-font{ font-family:'Plus Jakarta Sans',sans-serif; }

        /* ---------- Loading overlay ---------- */
        .page-loader{
            position:fixed; inset:0; z-index:9999;
            background:var(--bg);
            display:flex; align-items:center; justify-content:center; flex-direction:column;
            transition:opacity .4s ease, visibility .4s ease;
        }
        .page-loader.loaded{
            opacity:0; visibility:hidden; pointer-events:none;
        }
        .loader-spinner{
            width:44px; height:44px;
            border:4px solid #e0e7ff;
            border-top-color:var(--blue);
            border-radius:50%;
            animation:spin .7s linear infinite;
        }
        .loader-text{
            margin-top:.8rem;
            font-size:.82rem; font-weight:600; color:var(--muted);
            letter-spacing:.04em;
        }
        @keyframes spin{ to{ transform:rotate(360deg); } }

        /* ---------- Navbar ---------- */
        .navbar{
            background:linear-gradient(180deg, var(--blue) 0%, var(--blue-deep) 100%);
            box-shadow:0 4px 18px rgba(23,37,84,.18);
            padding:1rem 0;
        }
        .navbar .container{ display:flex; align-items:center; justify-content:space-between; gap:1rem; }
        .navbar .navbar-brand{
            color:#fff; font-weight:800; letter-spacing:.01em; line-height:1.15;
            margin:0; white-space:normal;
        }
        .btn-accent{
            background:linear-gradient(135deg,#fbbf24, #f59e0b);
            border:none; color:#172554; font-weight:700; border-radius:.65rem;
            box-shadow:0 6px 16px rgba(245,158,11,.35);
            transition:transform .15s ease, box-shadow .15s ease;
        }
        .btn-accent:hover{
            background:linear-gradient(135deg,#f59e0b,#d97706);
            color:#fff; transform:translateY(-2px);
            box-shadow:0 10px 22px rgba(245,158,11,.42);
        }
        .btn-outline-light{ border-width:1.5px; border-radius:.65rem; font-weight:600; }
        .btn-outline-light:hover{ background:#fff; color:var(--primary-dark); }

        /* ---------- Hero ---------- */
        .hero{
            position:relative;
            background:
                linear-gradient(120deg, rgba(20,40,90,.88) 0%, rgba(26,75,140,.72) 45%, rgba(59,130,246,.55) 100%),
                url("{{ asset('img/Backgroud.jpeg') }}") center/cover no-repeat;
            color:#fff;
            border-radius:1.5rem;
            padding:2.25rem 2.5rem;
            overflow:hidden;
            box-shadow:0 24px 60px -18px rgba(30,64,175,.5);
        }
        .hero::before{
            content:""; position:absolute; inset:0;
            background:
                radial-gradient(420px 420px at 88% -10%, rgba(255,255,255,.14), transparent 60%),
                radial-gradient(360px 360px at -8% 110%, rgba(245,158,11,.16), transparent 60%);
            pointer-events:none;
        }
        .eyebrow{
            display:inline-block;
            background:rgba(255,255,255,.14); border:1px solid rgba(255,255,255,.28);
            color:#fef9c3; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
            font-size:.72rem; padding:.42rem .9rem; border-radius:999px;
            backdrop-filter:blur(4px);
        }
        .hero h1{ font-weight:800; line-height:1.12; letter-spacing:-.01em; }
        .hero .lead{ color:#dbeafe; max-width:520px; }
        .hero-tagline .lead strong{ color:#fff; }

        /* Hero stat chips */
        .hero-chips{ display:flex; flex-wrap:wrap; gap:.6rem; }
        .hero-chip{
            display:inline-flex; align-items:center; gap:.6rem;
            background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.25);
            padding:.5rem .95rem; border-radius:14px; backdrop-filter:blur(4px);
        }
        .hero-chip b{ font-size:1.15rem; font-weight:800; font-family:'Plus Jakarta Sans',sans-serif; }
        .hero-chip span{ font-size:.74rem; color:#dbeafe; text-transform:uppercase; letter-spacing:.05em; }

        /* Mini dashboard preview card */
        .hero-visual{ position:relative; min-height:250px; }
        .dash-card{
            position:relative; width:290px; margin-left:auto;
            background:rgba(255,255,255,.97); border-radius:1.1rem; padding:1.15rem 1.25rem;
            box-shadow:0 26px 52px -18px rgba(10,30,70,.55);
            transform:rotate(-2deg);
        }
        .dash-head{ display:flex; align-items:center; gap:.7rem; margin-bottom:.95rem; }
        .dash-logo{
            width:40px;height:40px;border-radius:12px; flex:0 0 auto;
            background:linear-gradient(135deg,var(--blue),var(--primary-dark));
            display:flex;align-items:center;justify-content:center;color:#fff;
            box-shadow:0 8px 16px -6px rgba(30,64,175,.5);
        }
        .dash-title{ font-size:.88rem; font-weight:800; font-family:'Plus Jakarta Sans',sans-serif; line-height:1.2; color:var(--ink); }
        .dash-sub{ font-size:.66rem; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; font-weight:600; }
        .dash-stats{ display:flex; gap:.5rem; margin-bottom:1rem; }
        .dash-stat{
            flex:1; background:#f8fafc; border:1px solid #e6ebf4; border-radius:.8rem; padding:.6rem .4rem; text-align:center;
        }
        .dash-stat .num{ display:block; font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.2rem; line-height:1.1; color:var(--primary-dark); }
        .dash-stat .lbl{ font-size:.6rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; font-weight:600; }
        .dash-bars{ display:flex; flex-direction:column; gap:.7rem; }
        .dash-bar-head{ display:flex; justify-content:space-between; font-size:.68rem; color:var(--muted); margin-bottom:.28rem; }
        .dash-bar-head b{ color:var(--ink); font-weight:700; }
        .dash-bar{ height:7px; border-radius:999px; background:#e8edf4; overflow:hidden; }
        .dash-bar i{ display:block; height:100%; border-radius:999px; background:linear-gradient(90deg,var(--blue),var(--primary-dark)); }
        @media (max-width:991.98px){
            .hero{ padding:2rem 1.4rem; }
            .hero-visual{ display:none; }
        }

        /* ---------- Stat band ---------- */
        .stat-band{ margin-top:-2.1rem; }
        .stat-card{
            background:#fff; border:1px solid #e6ebf4; border-radius:1.1rem;
            box-shadow:0 10px 30px -14px rgba(23,37,84,.25);
            padding:1.15rem 1.3rem;
            display:flex; align-items:center; gap:1rem;
            transition:transform .18s ease, box-shadow .18s ease;
        }
        .stat-card:hover{ transform:translateY(-4px); box-shadow:0 16px 34px -14px rgba(23,37,84,.3); }
        .stat-icon{
            width:46px;height:46px;flex:0 0 auto;border-radius:13px;
            display:flex;align-items:center;justify-content:center;
            background:#e0e7ff; color:#4338ca;
        }
        .stat-icon.is-green{ background:#dbeafe; color:#1d4ed8; }
        .stat-icon.is-gray{ background:#f1f5f9; color:#64748b; }
        .stat-card .num{ font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.5rem; line-height:1; color:var(--ink); }
        .stat-card .lbl{ font-size:.74rem; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; font-weight:600; }
        .stat-card .sub{ font-size:.72rem; color:#94a3b8; }

        /* ---------- Section helpers ---------- */
        .section{ padding-top:4rem; }
        .section-head{ text-align:center; max-width:640px; margin:0 auto 2.5rem; }
        .section-head .kicker{
            display:inline-block; font-size:.72rem; font-weight:800; letter-spacing:.14em; text-transform:uppercase;
            color:var(--primary); background:#e0e7ff; padding:.35rem .8rem; border-radius:999px; margin-bottom:.8rem;
        }
        .section-head h2{ font-weight:800; letter-spacing:-.01em; }
        .section-head p{ color:var(--muted); }

        /* ---------- Feature cards ---------- */
        .feature-card{
            background:#fff; border:1px solid #e6ebf4; border-radius:1.1rem; padding:1.7rem 1.5rem;
            box-shadow:0 6px 20px -12px rgba(23,37,84,.18);
            transition:transform .2s ease, box-shadow .2s ease;
            position:relative; overflow:hidden; height:100%;
        }
        .feature-card::after{
            content:""; position:absolute; left:0; top:0; width:100%; height:4px;
            background:linear-gradient(90deg,var(--blue),var(--primary-dark)); opacity:0;
            transition:opacity .2s ease;
        }
        .feature-card:hover{ transform:translateY(-6px); box-shadow:0 22px 44px -18px rgba(23,37,84,.3); }
        .feature-card:hover::after{ opacity:1; }
        .feature-card .icon{
            width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;
            background:#eef2ff;color:var(--primary);margin-bottom:1.1rem;
        }
        .feature-card h3{ font-weight:800; font-size:1.02rem; margin-bottom:.5rem; }
        .feature-card p{ font-size:.86rem; color:var(--muted); margin-bottom:0; line-height:1.6; }

        /* ---------- Process steps ---------- */
        .step-item{ position:relative; }
        .step-item .step-num{
            width:46px;height:46px;border-radius:50%; margin:0 auto .8rem;
            background:linear-gradient(140deg,var(--blue),var(--primary-dark));
            color:#fff; font-weight:800; font-family:'Plus Jakarta Sans',sans-serif;
            display:flex;align-items:center;justify-content:center; font-size:1.05rem;
            box-shadow:0 8px 18px -6px rgba(30,64,175,.45);
        }
        .step-item h4{ font-weight:800; font-size:1rem; }
        .step-item p{ font-size:.85rem; color:var(--muted); }
        .step-connector{
            position:absolute; top:23px; left:calc(50% + 30px); right:calc(-50% + 30px);
            border-top:2px dashed #cbd5e1;
        }
        @media (max-width:991.98px){ .step-connector{ display:none; } }

        /* ---------- CTA band ---------- */
        .cta-band{
            position:relative; overflow:hidden;
            background:
                linear-gradient(120deg, rgba(20,40,90,.88) 0%, rgba(26,75,140,.72) 45%, rgba(59,130,246,.55) 100%),
                url("{{ asset('img/Backgroud.jpeg') }}") center/cover no-repeat;
            border-radius:1.5rem; padding:3rem 2rem; color:#fff;
            box-shadow:0 24px 60px -18px rgba(30,64,175,.5);
        }
        .cta-band::before{
            content:""; position:absolute; inset:0;
            background:radial-gradient(420px 300px at 100% 0%, rgba(255,255,255,.16), transparent 60%);
            pointer-events:none;
        }
        .cta-band h2{ font-weight:800; }
        .cta-band p{ color:#dbeafe; }

        /* ---------- Footer ---------- */
        .footer{ color:#64748b; font-size:.83rem; }
        .footer .brand-name{ color:var(--primary-dark); font-weight:800; }

        /* =====================================================
           MOBILE (max-width: 575.98px)
           Hanya menyentuh tampilan HP, laptop tidak berubah.
        ===================================================== */
        @media (max-width:575.98px){
            /* Navbar lebih ringkas */
            .navbar{ padding:.75rem 0; }
            .navbar .navbar-brand{ font-size:.92rem; line-height:1.25; }
            .navbar .btn-accent{ padding-left:.9rem; padding-right:.9rem; font-size:.82rem; }

            /* Hero */
            .hero{ padding:1.75rem 1.25rem; border-radius:1.1rem; }
            .hero h1{ font-size:1.55rem; line-height:1.2; }
            .hero .lead{ font-size:.92rem; margin-top:.8rem; margin-bottom:1.2rem !important; }
            .hero .btn-lg{ font-size:.9rem; padding:.55rem .9rem; }
            .hero-tagline .lead{ max-width:100%; }

            /* Stat chips hero */
            .hero-chips{ gap:.45rem; }
            .hero-chip{ padding:.42rem .7rem; gap:.45rem; border-radius:12px; }
            .hero-chip b{ font-size:1rem; }
            .hero-chip span{ font-size:.64rem; }

            /* Stat band di bawah hero */
            .stat-band{ margin-top:-1.6rem; }
            .stat-card{ padding:.9rem 1rem; gap:.75rem; }
            .stat-icon{ width:40px;height:40px;border-radius:11px; }
            .stat-card .num{ font-size:1.3rem; }
            .stat-card .lbl{ font-size:.66rem; }
            .stat-card .sub{ font-size:.66rem; }

            /* Section */
            .section{ padding-top:2.6rem; }
            .section-head{ margin-bottom:1.6rem; }
            .section-head h2{ font-size:1.35rem; }
            .section-head p{ font-size:.88rem; }

            /* Feature card */
            .feature-card{ padding:1.35rem 1.2rem; }
            .feature-card .icon{ width:44px;height:44px;border-radius:12px; margin-bottom:.9rem; }
            .feature-card h3{ font-size:.98rem; }
            .feature-card p{ font-size:.84rem; }

            /* Process steps */
            .step-item .step-num{ width:40px;height:40px;font-size:.95rem; }
            .step-item h4{ font-size:.95rem; }
            .step-item p{ font-size:.84rem; }

            /* CTA band */
            .cta-band{ padding:2.1rem 1.25rem; border-radius:1.1rem; }
            .cta-band h2{ font-size:1.35rem; }
            .cta-band p{ font-size:.88rem; }
            .cta-band .btn-lg{ font-size:.88rem; padding:.55rem .9rem; }

            /* Footer */
            .footer{ font-size:.78rem; }
        }
    </style>
</head>
<body>

    {{-- ===== Loading Overlay ===== --}}
    <div class="page-loader" id="pageLoader">
        <div class="loader-spinner"></div>
        <div class="loader-text">Memuat halaman...</div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">PENDAFTARAN CALON KARYAWAN</a>
            <a class="btn btn-accent btn-sm px-3" href="{{ route('calon-karyawan.index') }}">Daftar</a>
        </div>
    </nav>

    <div class="container py-4">

        {{-- ===== Hero ===== --}}
        <section class="hero" data-aos="fade-up" data-aos-duration="800">
            <div class="row align-items-center position-relative">
                <div class="col-lg-7">
                    <h1 class="display-font" data-aos="fade-up" data-aos-delay="160">
                        Kelola Data Calon Karyawan<br class="d-none d-md-block"> Dengan <span class="text-warning">Mudah</span> &amp; Rapi
                    </h1>
                    <p class="lead mt-3 mb-4 hero-tagline" data-aos="fade-up" data-aos-delay="240">
                        Catat, cari, dan kelola data calon karyawan dalam satu tempat.
                        Praktis digunakan di <strong>laptop</strong> maupun <strong>HP</strong>.
                    </p>

                    <div class="d-flex flex-column flex-sm-row flex-wrap gap-2 mb-4" data-aos="fade-up" data-aos-delay="320">
                        <a href="{{ route('calon-karyawan.index') }}" class="btn btn-accent btn-lg px-4">Lihat Daftar Calon Karyawan</a>
                        <a href="{{ route('calon-karyawan.create') }}" class="btn btn-outline-light btn-lg px-4">+ Tambah Karyawan Baru</a>
                    </div>

                    <div class="hero-chips" data-aos="fade-up" data-aos-delay="400">
                        <div class="hero-chip">
                            <b>{{ number_format($total) }}</b><span>Kandidat</span>
                        </div>
                        <div class="hero-chip">
                            <b>{{ number_format($totalAktif) }}</b><span>Aktif</span>
                        </div>
                        <div class="hero-chip">
                            <b>{{ number_format($totalNonaktif) }}</b><span>Nonaktif</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="hero-visual" data-aos="zoom-in" data-aos-delay="350">
                        <div class="dash-card">
                            <div class="dash-head">
                                <div class="dash-logo">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                <div>
                                    <div class="dash-title">Data Calon Karyawan</div>
                                    <div class="dash-sub">Ringkasan rekrutmen</div>
                                </div>
                            </div>
                            <div class="dash-stats">
                                <div class="dash-stat">
                                    <span class="num">{{ number_format($total) }}</span>
                                    <span class="lbl">Total</span>
                                </div>
                                <div class="dash-stat">
                                    <span class="num">{{ number_format($totalAktif) }}</span>
                                    <span class="lbl">Aktif</span>
                                </div>
                                <div class="dash-stat">
                                    <span class="num">{{ number_format($totalNonaktif) }}</span>
                                    <span class="lbl">Nonaktif</span>
                                </div>
                            </div>
                            <div class="dash-bars">
                                <div>
                                    <div class="dash-bar-head">
                                        <span>Kandidat aktif</span>
                                        <b>{{ $total ? round($totalAktif / $total * 100) : 0 }}%</b>
                                    </div>
                                    <div class="dash-bar"><i style="width:{{ $total ? round($totalAktif / $total * 100) : 0 }}%"></i></div>
                                </div>
                                <div>
                                    <div class="dash-bar-head">
                                        <span>Kandidat nonaktif</span>
                                        <b>{{ $total ? round($totalNonaktif / $total * 100) : 0 }}%</b>
                                    </div>
                                    <div class="dash-bar"><i style="width:{{ $total ? round($totalNonaktif / $total * 100) : 0 }}%"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== Statistik ===== --}}
        <section class="stat-band" data-aos="fade-up" data-aos-delay="150">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="stat-card h-100">
                        <div class="stat-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <div class="num">{{ number_format($total) }}</div>
                            <div class="lbl">Total Kandidat</div>
                            <div class="sub">Tercatat di sistem</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-card h-100">
                        <div class="stat-icon is-green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <div class="num">{{ number_format($totalAktif) }}</div>
                            <div class="lbl">Kandidat Aktif</div>
                            <div class="sub">Dalam proses rekrutmen</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-card h-100">
                        <div class="stat-icon is-gray">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <div class="num">{{ number_format($totalNonaktif) }}</div>
                            <div class="lbl">Kandidat Nonaktif</div>
                            <div class="sub">Sudah tidak aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== Kenapa sistem ini ===== --}}
        <section class="section">
            <div class="section-head" data-aos="fade-up">
                <span class="kicker">Keunggulan</span>
                <h2 class="h3">Kenapa Menggunakan Sistem Ini?</h2>
                <p>Semua kebutuhan pencatatan data calon karyawan dirangkum menjadi satu alur kerja yang sederhana dan tertata.</p>
            </div>
            <div class="row g-3">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
                        </div>
                        <h3>Data Lengkap &amp; Terstruktur</h3>
                        <p>Data pribadi, pekerjaan, dokumen, rekening, hingga kerabat tercatat rapi dalam satu berkas.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        </div>
                        <h3>Mudah Dicari</h3>
                        <p>Cari calon karyawan berdasarkan nama atau kode, dan saring status aktif maupun nonaktif.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        </div>
                        <h3>Responsif di Semua Perangkat</h3>
                        <p>Tampilan menyesuaikan layar laptop, tablet, hingga HP agar nyaman digunakan siapa saja.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== Alur kerja ===== --}}
        <section class="section">
            <div class="section-head" data-aos="fade-up">
                <span class="kicker">Cara Kerja</span>
                <h2 class="h3">Tiga Langkah Sederhana</h2>
                <p>Mulai dari mencatat kandidat baru, melengkapi datanya, sampai siap diproses bagian HRD.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-item text-center" data-aos="fade-up" data-aos-delay="100">
                        <span class="step-connector"></span>
                        <div class="step-num">1</div>
                        <h4>Tambah Kandidat</h4>
                        <p>Buat akun calon karyawan baru dengan data awal yang ringkas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-item text-center" data-aos="fade-up" data-aos-delay="200">
                        <span class="step-connector"></span>
                        <div class="step-num">2</div>
                        <h4>Lengkapi Data</h4>
                        <p>Isi dokumen, pekerjaan, rekening, hingga data kerabat secara bertahap.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="step-item text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="step-num">3</div>
                        <h4>Siap Diproses</h4>
                        <p>Semua data siap direview dan diarsipkan oleh tim HRD.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== CTA ===== --}}
        <section class="section" data-aos="fade-up">
            <div class="cta-band text-center">
                <h2 class="h3 mb-2">Siap Mengelola Data Karyawan?</h2>
                <p class="mb-4">Mulai catat kandidat pertama Anda sekarang &mdash; mudah, rapi, dan siap pakai.</p>
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="{{ route('calon-karyawan.create') }}" class="btn btn-accent btn-lg px-4">+ Tambah Karyawan Baru</a>
                    <a href="{{ route('calon-karyawan.index') }}" class="btn btn-outline-light btn-lg px-4">Lihat Daftar Kandidat</a>
                </div>
            </div>
        </section>

        {{-- ===== Footer ===== --}}
        <footer class="footer text-center py-4 mt-5 border-top" data-aos="fade-in">
            &copy; {{ date('Y') }} <span class="brand-name">Altius</span> &middot; Modul Rekrutmen Data Calon Karyawan.
        </footer>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Loading state — hilangkan overlay saat halaman selesai load
        window.addEventListener('load', function () {
            document.getElementById('pageLoader').classList.add('loaded');
        });

        AOS.init({
            duration: 700,
            easing: 'ease-out-cubic',
            once: true,
            offset: 60
        });

        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.addEventListener('pageshow', function (e) {
            var nav = performance.getEntriesByType('navigation')[0];
            if (e.persisted || (nav && nav.type === 'reload')) {
                window.scrollTo(0, 0);
            }
        });
    </script>
</body>
</html>
