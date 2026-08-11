<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Modul HRD') | Sistem HRD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{
            --ink:#0f172a;
            --muted:#475569;
            --line:#e2e8f0;
            --bg:#f1f5f9;
            --primary:#1e40af;
            --primary-dark:#172554;
            --accent:#f59e0b;
            --accent-soft:#fef3c7;
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
        body{
            background:var(--bg);
            color:var(--ink);
            font-family:'Inter',sans-serif;
            padding-bottom:3rem;
        }
        h1,h2,h3,h4,.brand,.step-label{
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .navbar{
            background: linear-gradient(180deg, #4A90E2 0%, #1A4B8C 100%);
            box-shadow:0 2px 10px rgba(23,37,84,.25);
        }
        .navbar .navbar-brand{
            color:#fff;
            font-weight:800;
            letter-spacing:-.01em;
            font-size:1.03rem;
            line-height:1.1;
        }
        .navbar .navbar-brand small{
            display:block;
            font-family:'Inter',sans-serif;
            font-weight:500;
            font-size:.68rem;
            color:#c7d2fe;
            letter-spacing:.08em;
            text-transform:uppercase;
        }
        .navbar .nav-link{
            color:#dbeafe;
            font-weight:600;
            font-size:.9rem;
            border-radius:.6rem;
            padding:.45rem .7rem;
        }
        .navbar .nav-link:hover{
            color:#fff;
            background:rgba(255,255,255,.12);
        }
        .navbar .nav-link.active{
            color:#fff;
            background:rgba(255,255,255,.18);
            box-shadow:inset 0 -2px 0 rgba(255,255,255,.9);
        }
        .navbar .navbar-toggler{
            border-color:rgba(255,255,255,.35);
        }
        .navbar .navbar-toggler-icon{
            filter:invert(1);
        }
        .btn-accent{
            background:var(--accent);
            border-color:var(--accent);
            color:#172554;
            font-weight:700;
            border-radius:.5rem;
        }
        .btn-accent:hover{
            background:#d97706;
            border-color:#d97706;
            color:#fff;
        }
        .shell{
            max-width:1100px;
            margin:2rem auto;
            padding:0 1rem;
        }
        .card-panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius:14px;
            box-shadow:0 1px 2px rgba(15,23,42,.04);
        }
        .form-label{
            font-size:.8rem;
            font-weight:600;
            color:var(--muted);
            margin-bottom:.3rem;
        }
        .form-control, .form-select{
            border-color:var(--line);
            font-size:.92rem;
        }
        .form-control:focus, .form-select:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 .2rem rgba(30,64,175,.12);
        }
        .input-group{ align-items:center; }
        .input-group-text{
            background:#f8fafc;
            color:var(--muted);
            border-color:var(--line);
            padding:.5rem .7rem;
        }
        .input-group-text svg{ display:block; }
        .input-group:focus-within .input-group-text{
            color:var(--primary);
            border-color:var(--primary);
            box-shadow:0 0 0 .2rem rgba(30,64,175,.12);
        }
        .btn-primary-soft{
            background: linear-gradient(180deg, #4A90E2 0%, #1A4B8C 100%);
            border-color:var(--primary);
            color:#fff;
            font-weight:600;
        }
        .btn-primary-soft:hover{
            background:var(--primary-dark);
            border-color:var(--primary-dark);
            color:#fff;
        }
        .table thead th{
            font-size:.72rem;
            text-transform:uppercase;
            letter-spacing:.06em;
            color:var(--muted);
            border-bottom-width:2px;
        }
        .table tbody td{
            vertical-align:middle;
        }

        /* ---------- UI kit (corporate, HRD) ---------- */
        .page-head{ display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:.75rem; margin-bottom:1.25rem; }
        .page-head h1{ font-size:1.3rem; font-weight:800; margin:0; letter-spacing:-.01em; }
        .page-head .page-sub{ color:var(--muted); font-size:.85rem; margin:0; }

        .metric-card{
            background:#fff; border:1px solid var(--line); border-radius:12px;
            padding:1rem 1.15rem; height:100%;
        }
        .metric-card .metric-label{ font-size:.72rem; text-transform:uppercase; letter-spacing:.07em; font-weight:700; color:var(--muted); }
        .metric-card .metric-value{ font-size:1.7rem; font-weight:800; line-height:1.1; color:var(--ink); font-family:'Plus Jakarta Sans',sans-serif; }
        .metric-card .metric-sub{ font-size:.75rem; color:#94a3b8; }

        .data-toolbar{
            background:#fff; border:1px solid var(--line); border-radius:12px;
            padding:.9rem 1rem; margin-bottom:1rem;
            display:flex; flex-wrap:wrap; gap:.6rem; align-items:center;
        }
        .data-toolbar .form-control,.data-toolbar .form-select{ border-radius:.55rem; }
        .data-toolbar .search-wrap{ position:relative; flex:1 1 220px; }
        .data-toolbar .search-wrap svg{ position:absolute; left:.7rem; top:50%; transform:translateY(-50%); color:var(--muted); }
        .data-toolbar .search-wrap input{ padding-left:2.1rem; }

        .btn-action{
            display:inline-flex; align-items:center; gap:.35rem;
            font-size:.78rem; font-weight:600;
            border-radius:.5rem; padding:.3rem .55rem;
            white-space:nowrap;
        }

        .empty-state{
            text-align:center; padding:2.6rem 1.5rem; color:var(--muted);
        }
        .empty-state .empty-icon{
            width:52px; height:52px; margin:0 auto .8rem; border-radius:14px;
            background:#f1f5f9; color:#94a3b8;
            display:flex; align-items:center; justify-content:center;
        }
        .empty-state h3{ font-size:1rem; font-weight:700; color:var(--ink); margin-bottom:.3rem; }
        .empty-state p{ font-size:.85rem; margin-bottom:.9rem; }

        .status-badge{
            display:inline-flex; align-items:center; gap:.35rem;
            font-size:.72rem; font-weight:700; padding:.3rem .6rem; border-radius:999px;
            white-space:nowrap;
        }
        .status-badge::before{ content:""; width:6px; height:6px; border-radius:50%; background:currentColor; }
        .status-badge.is-aktif{ background:#ecfdf5; color:#047857; }
        .status-badge.is-nonaktif{ background:#f1f5f9; color:#64748b; }

        .table thead th{ background:#f8fafc; }
        .table-hover tbody tr:hover{ background:#f8fafc; }
        .table td .text-empty{ color:#cbd5e1; }

        .table-sm-actions .btn-action > span{ display:inline; }
        @media (max-width:767.98px){
            .table-sm-actions .btn-action > span{ display:none; }
        }

        .form-hint{ font-size:.75rem; color:#94a3b8; margin-top:.3rem; }
        .required-mark{ color:#dc2626; }

        /* ------- Wizard stepper ------- */
        .wizard-progress{
            height:6px;
            background:#e8edf4;
            border-radius:999px;
            overflow:hidden;
            margin-bottom:1.1rem;
        }
        .wizard-progress .progress-fill{
            height:100%;
            border-radius:999px;
            background:linear-gradient(90deg,#4A90E2,#1A4B8C);
            transition:width .3s ease;
        }
        .wizard-stepbar{
            display:flex;
            flex-wrap:wrap;
            gap:.3rem .5rem;
            align-items:center;
            margin-bottom:1rem;
        }
        .wizard-stepbar .w-title{
            font-size:.78rem;
            font-weight:600;
            color:var(--primary-dark);
        }
        .wizard-stepbar .w-indicator{
            display:none;
        }
        .step-sidebar{
            display:flex;
            flex-direction:column;
            gap:.35rem;
            position:sticky;
            top:80px;
        }
        .step-sidebar .step-btn{
            display:flex;
            align-items:center;
            gap:.65rem;
            text-align:left;
            width:100%;
            border:1px solid transparent;
            background:none;
            border-radius:.7rem;
            padding:.62rem .75rem;
            font-size:.86rem;
            font-weight:600;
            color:var(--muted);
            white-space:nowrap;
            transition:background .15s ease,color .15s ease,border-color .15s ease;
        }
        .step-sidebar .step-btn:hover{
            background:#f1f5f9;
            color:var(--ink);
        }
        .step-sidebar .step-btn.active{
            color:#fff;
            border-color:var(--primary-dark);
            background:linear-gradient(180deg,#4A90E2,#1A4B8C);
            box-shadow:0 3px 8px rgba(30,64,175,.22);
        }
        .step-sidebar .step-btn .step-num{
            flex:0 0 auto;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:24px;height:24px;
            border-radius:50%;
            font-size:.72rem;
            font-weight:700;
            border:1.5px solid var(--line);
            color:var(--muted);
            background:#fff;
        }
        .step-sidebar .step-btn.active .step-num{
            border-color:#fff;
            color:#172554;
            background:#fff;
        }
        /* ---------- Pane crossfade ---------- */
        .section-pane{
            display:none; padding:1.5rem;
            will-change:opacity, transform;
        }
        .section-pane.active{
            display:block;
            animation: paneIn .65s cubic-bezier(.16,1,.3,1) forwards;
        }
        .section-pane.pane-exit{
            display:block;
            animation: paneOut .35s cubic-bezier(.55,.06,.68,.19) forwards;
        }
        @keyframes paneIn{
            0%  { opacity:0; transform:translateY(20px) scale(.97); filter:blur(6px); }
            60% { filter:blur(0); }
            100%{ opacity:1; transform:translateY(0)    scale(1);  filter:blur(0); }
        }
        @keyframes paneOut{
            0%  { opacity:1; transform:translateY(0)   scale(1);  filter:blur(0); }
            100%{ opacity:0; transform:translateY(-10px) scale(.98); filter:blur(4px); }
        }

        /* ---------- Staggered field reveal (organic wave) ---------- */
        .section-pane.active .row.g-3 > [class*="col-"]{
            opacity:0; transform:translateY(12px) scale(.98);
            animation: fieldUp .6s cubic-bezier(.16,1,.3,1) forwards;
            will-change:opacity, transform;
        }
        /* Delay pakai kurva eksponensial — awal cepat, akhir melambat */
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(1)  { animation-delay:.07s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(2)  { animation-delay:.12s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(3)  { animation-delay:.17s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(4)  { animation-delay:.22s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(5)  { animation-delay:.27s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(6)  { animation-delay:.31s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(7)  { animation-delay:.35s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(8)  { animation-delay:.38s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(9)  { animation-delay:.41s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(10) { animation-delay:.44s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(11) { animation-delay:.46s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(12) { animation-delay:.48s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(13) { animation-delay:.50s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(14) { animation-delay:.52s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(15) { animation-delay:.53s; }
        .section-pane.active .row.g-3 > [class*="col-"]:nth-child(n+16){ animation-delay:.54s; }
        @keyframes fieldUp{
            0%  { opacity:0; transform:translateY(12px) scale(.98); }
            100%{ opacity:1; transform:translateY(0)    scale(1); }
        }

        /* ---------- Section title ---------- */
        .section-pane.active .section-title{
            opacity:0;
            animation: titleIn .55s cubic-bezier(.16,1,.3,1) .05s forwards;
        }
        @keyframes titleIn{
            0%  { opacity:0; transform:translateX(-10px); letter-spacing:.14em; }
            100%{ opacity:1; transform:translateX(0);     letter-spacing:.09em; }
        }

        /* ---------- Bottom buttons ---------- */
        .section-pane.active .d-flex.justify-content-between,
        .section-pane.active .text-end{
            opacity:0;
            animation: fieldUp .5s cubic-bezier(.16,1,.3,1) .58s forwards;
        }

        /* ---------- Progress bar smoother ---------- */
        .wizard-progress .progress-fill{
            transition:width .6s cubic-bezier(.16,1,.3,1);
        }
        .section-title{
            font-size:.72rem;
            font-weight:700;
            letter-spacing:.09em;
            text-transform:uppercase;
            color:var(--primary);
            margin-bottom:1.1rem;
        }
        .kode-badge{
            display:inline-block;
            background:#eef2ff;
            color:var(--primary-dark);
            font-weight:700;
            padding:.35rem .7rem;
            border-radius:8px;
            font-size:.85rem;
            white-space:nowrap;
        }
        .kerabat-row{ border:1px dashed var(--line); border-radius:10px; padding:1rem; margin-bottom:.75rem; }
        .remove-kerabat{ font-size:.78rem; }
        .photo-drop{
            border:1.5px dashed var(--line);
            border-radius:12px;
            padding:1.5rem;
            text-align:center;
            color:var(--muted);
            background:#fafbfd;
            cursor:pointer;
        }
        #fotoPreview{ max-height:140px; border-radius:10px; display:none; cursor:zoom-in; }

        /* Mobile: sidebar disembunyikan, fallback ke breadcrumb step */
        @media (max-width: 991.98px){
            .step-sidebar{ display:none; }
            .wizard-stepbar .w-indicator{ display:inline-flex; }
        }
        /* Responsive: padding form lebih ringkas di HP */
        @media (max-width: 575.98px){
            .shell{ margin:.9rem auto; }
            .section-pane{ padding:1.1rem; }
            .wizard-stepbar{ gap:.25rem .4rem; }
            .wizard-stepbar .w-title{ font-size:.75rem; }
        }

        /* =====================================================
           MOBILE (max-width: 575.98px)
           Hanya menyentuh tampilan HP, laptop tidak berubah.
        ===================================================== */
        @media (max-width: 575.98px){
            body{ padding-bottom:2rem; }

            /* Navbar lebih ringkas */
            .navbar{ padding:.65rem 0; }
            .navbar .navbar-brand{ font-size:.95rem; line-height:1.25; }
            .navbar .navbar-brand small{ font-size:.62rem; letter-spacing:.06em; }
            .navbar .nav-link{ font-size:.95rem; }
            .navbar .btn-accent{ font-size:.85rem; padding-left:.9rem; padding-right:.9rem; }
            .navbar .navbar-toggler{ padding:.3rem .5rem; }
            .navbar-collapse{ padding-top:.4rem; }
            .navbar-collapse .nav-link{ padding:.55rem .25rem; border-top:1px solid rgba(255,255,255,.14); }
            .navbar-collapse .nav-item:last-child{ border-bottom:1px solid rgba(255,255,255,.14); }
            .navbar-collapse .btn-accent{ margin-top:.6rem; }

            /* Container / kartu */
            .shell{ padding:0 .75rem; }
            .card-panel{ border-radius:12px; }
            .card-panel.p-3{ padding:.9rem .85rem !important; }

            /* Heading halaman */
            h1.h4{ font-size:1.2rem; }
            .shell > div:first-child .text-secondary.small{ font-size:.8rem; }

            /* Form control lebih nyaman disentuh */
            .form-label{ font-size:.78rem; }
            .form-control, .form-select{ font-size:.95rem; padding:.55rem .7rem; }
            .input-group-text{ padding:.55rem .6rem; }
            .input-group-text svg{ width:15px;height:15px; }

            /* Tombol di mobile memakai lebar penuh pada halaman daftar */
            .btn{ white-space:normal; }
            .btn-primary-soft, .btn-accent{ padding-top:.55rem; padding-bottom:.55rem; }
            .btn-group-sm .btn{ padding:.3rem .45rem; }

            /* Wizard stepper */
            .wizard-stepbar .w-title{ font-size:.8rem; }
            .wizard-stepbar .w-indicator .step-num{ width:22px;height:22px;font-size:.68rem; }
            #candidateForm .next-step,
            #candidateForm .prev-step,
            #candidateForm .btn-primary-soft,
            #candidateForm .btn-outline-secondary{ width:100%; }
            #candidateForm .btn-outline-secondary{ border-color:var(--line); }

            /* Tabel: biar tidak terlalu rapat di HP */
            .table{ font-size:.85rem; }

            /* Detail calon karyawan: label & nilai bertumpuk di HP */
            dl.row dt, dl.row dd{ width:100% !important; }
            dl.row dt{ margin-bottom:.1rem; }
            dl.row dd{ padding-left:0 !important; margin-bottom:.6rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                AltiusMagang
                <small>Sistem Manajemen HRD</small>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    @unless (request()->routeIs('calon-karyawan.*'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('calon-karyawan.index') }}">Calon Karyawan</a>
                        </li>
                    @endunless
                    <li class="nav-item">
                        <a class="btn btn-accent btn-sm ms-lg-2 my-2 my-lg-0" href="{{ route('calon-karyawan.create') }}">+ Tambah</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="shell">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @yield('content')
    </div>

    {{-- Lightbox: klik foto kecil (data-foto-lightbox) utk lihat besar, gaya IG --}}
    <div class="modal fade" id="fotoLightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
            <div class="modal-content bg-transparent border-0">
                <div class="text-end mb-2">
                    <button type="button" class="btn btn-light btn-sm rounded-circle" data-bs-dismiss="modal" aria-label="Tutup">&times;</button>
                </div>
                <img id="fotoLightboxImg" src="" alt="Foto kandidat" class="img-fluid rounded mx-auto d-block"
                                 style="max-height:90vh;width:auto;max-width:90vw;background:#fff;border:1px solid var(--line);box-shadow:0 5px 30px rgba(15,23,42,.4)">
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Matikan fitur browser yang mengingat posisi scroll agar
        // saat refresh halaman selalu terbuka dari atas.
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.addEventListener('pageshow', function (e) {
            if (e.persisted || performance.navigation.type === performance.navigation.TYPE_RELOAD) {
                window.scrollTo(0, 0);
            }
        });

        // Lightbox foto: elemen ber-atribut data-foto-lightbox -> klik -> tampil besar
        document.addEventListener('click', function (e) {
            const trigger = e.target.closest('[data-foto-lightbox]');
            if (!trigger) return;
            const src = trigger.dataset.fotoSrc || trigger.currentSrc || trigger.src;
            if (!src) return;
            const modal = document.getElementById('fotoLightboxModal');
            document.getElementById('fotoLightboxImg').src = src;
            bootstrap.Modal.getOrCreateInstance(modal).show();
        });
    </script>
</body>
</html>
