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
            background:var(--primary);
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
        .btn-primary-soft{
            background:var(--primary);
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

        .nav-steps{
            display:flex;
            gap:.4rem;
            border-bottom:1px solid var(--line);
            padding:0 1.25rem;
            overflow-x:auto;
        }
        .nav-steps .step-btn{
            border:none;
            background:none;
            padding:1rem .5rem;
            font-size:.86rem;
            font-weight:600;
            color:#8b98ae;
            white-space:nowrap;
            border-bottom:3px solid transparent;
        }
        .nav-steps .step-btn.active{
            color:var(--primary);
            border-bottom-color:var(--accent);
        }
        .step-label .num{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:20px;height:20px;
            border-radius:50%;
            background:var(--line);
            color:var(--muted);
            font-size:.7rem;
            margin-right:.4rem;
        }
        .step-btn.active .num{
            background:var(--primary);
            color:#fff;
        }
        .section-pane{ display:none; padding:1.5rem; }
        .section-pane.active{ display:block; }
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

        /* Responsive: padding form lebih ringkas di HP */
        @media (max-width: 575.98px){
            .shell{ margin:1rem auto; }
            .section-pane{ padding:1.1rem; }
            .nav-steps{ padding:0 .75rem; }
            .nav-steps .step-btn{ padding:.9rem .4rem; font-size:.8rem; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Sistem HRD <small>Modul Rekrutmen &middot; Data Calon Karyawan</small></a>
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
