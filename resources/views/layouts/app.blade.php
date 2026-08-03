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
            --ink:#16233a;
            --slate:#425271;
            --line:#dfe4ee;
            --paper:#f6f7fb;
            --teal:#0f7a6b;
            --teal-dark:#0b5c50;
            --amber:#c8792a;
        }
        body{
            background:var(--paper);
            color:var(--ink);
            font-family:'Inter',sans-serif;
        }
        h1,h2,h3,h4,.brand,.step-label{
            font-family:'Plus Jakarta Sans',sans-serif;
        }
        .topbar{
            background:var(--ink);
            color:#fff;
            padding:.9rem 1.5rem;
        }
        .topbar .brand{
            font-weight:800;
            letter-spacing:.02em;
            font-size:1.05rem;
        }
        .topbar .brand small{
            display:block;
            font-family:'Inter',sans-serif;
            font-weight:400;
            font-size:.72rem;
            color:#9fb0c8;
            letter-spacing:.08em;
            text-transform:uppercase;
        }
        .shell{
            max-width:1100px;
            margin:2rem auto;
            padding:0 1rem 4rem;
        }
        .card-panel{
            background:#fff;
            border:1px solid var(--line);
            border-radius:14px;
            box-shadow:0 1px 2px rgba(22,35,58,.04);
        }
        .form-label{
            font-size:.8rem;
            font-weight:600;
            color:var(--slate);
            margin-bottom:.3rem;
        }
        .form-control, .form-select{
            border-color:var(--line);
            font-size:.92rem;
        }
        .form-control:focus, .form-select:focus{
            border-color:var(--teal);
            box-shadow:0 0 0 .2rem rgba(15,122,107,.12);
        }
        .btn-teal{
            background:var(--teal);
            border-color:var(--teal);
            color:#fff;
            font-weight:600;
        }
        .btn-teal:hover{ background:var(--teal-dark); border-color:var(--teal-dark); color:#fff; }

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
            color:var(--ink);
            border-bottom-color:var(--teal);
        }
        .step-label .num{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:20px;height:20px;
            border-radius:50%;
            background:var(--line);
            color:var(--slate);
            font-size:.7rem;
            margin-right:.4rem;
        }
        .step-btn.active .num{
            background:var(--teal);
            color:#fff;
        }
        .section-pane{ display:none; padding:1.75rem; }
        .section-pane.active{ display:block; }
        .section-title{
            font-size:.72rem;
            font-weight:700;
            letter-spacing:.09em;
            text-transform:uppercase;
            color:var(--teal-dark);
            margin-bottom:1.1rem;
        }
        .kode-badge{
            display:inline-block;
            background:#eaf4f2;
            color:var(--teal-dark);
            font-weight:700;
            padding:.35rem .7rem;
            border-radius:8px;
            font-size:.85rem;
        }
        .kerabat-row{ border:1px dashed var(--line); border-radius:10px; padding:1rem; margin-bottom:.75rem; }
        .remove-kerabat{ font-size:.78rem; }
        .photo-drop{
            border:1.5px dashed var(--line);
            border-radius:12px;
            padding:1.5rem;
            text-align:center;
            color:var(--slate);
            background:#fafbfd;
            cursor:pointer;
        }
        #fotoPreview{ max-height:140px; border-radius:10px; display:none; }
    </style>
</head>
<body>
    <div class="topbar d-flex justify-content-between align-items-center">
        <div class="brand">Sistem HRD <small>Modul Rekrutmen &middot; Data Calon Karyawan</small></div>
        <a href="{{ route('calon-karyawan.index') }}" class="text-white text-decoration-none small">&larr; Daftar Calon Karyawan</a>
    </div>
    <div class="shell">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </div>
</body>
</html>
