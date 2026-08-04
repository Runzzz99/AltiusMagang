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
            letter-spacing:.02em;
            font-size:1.05rem;
        }
        .navbar .navbar-brand small{
            display:block;
            font-family:'Inter',sans-serif;
            font-weight:400;
            font-size:.7rem;
            color:#c7d2fe;
            letter-spacing:.08em;
            text-transform:uppercase;
        }
        .navbar .nav-link{
            color:#dbeafe;
            font-weight:500;
            font-size:.9rem;
        }
        .navbar .nav-link:hover,
        .navbar .nav-link.active{
            color:#fff;
        }
        .navbar .nav-link.active{
            font-weight:600;
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
        .section-pane{ display:none; padding:1.5rem; }
        .section-pane.active{ display:block; animation: paneFadeUp .55s cubic-bezier(.25,.46,.45,.94) both; }
        @keyframes paneFadeUp{
            from{ opacity:0; transform:translateY(18px); }
            to{ opacity:1; transform:translateY(0); }
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
        #fotoPreview{ max-height:140px; border-radius:10px; display:none; }

        /* Mobile: sidebar disembunyikan, fallback ke breadcrumb step */
        @media (max-width: 991.98px){
            .step-sidebar{ display:none; }
            .wizard-stepbar .w-indicator{ display:inline-flex; }
        }
        /* Responsive: padding form lebih ringkas di HP */
        @media (max-width: 575.98px){
            .shell{ margin:1rem auto; }
            .section-pane{ padding:1.1rem; }
            .wizard-stepbar{ gap:.25rem .4rem; }
            .wizard-stepbar .w-title{ font-size:.75rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">PENDAFTARAN CALON KARYAWAN<small> &middot;Buat akun karyawan baru</small></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('calon-karyawan.*') ? 'active' : '' }}" href="{{ route('calon-karyawan.index') }}">Daftar Calon Karyawan</a>
                    </li>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
