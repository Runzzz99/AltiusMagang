<?php

namespace App\Http\Controllers;

use App\Models\CalonKaryawan;
use Illuminate\Http\Request;

class CalonKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonKaryawan::orderByDesc('TglEntry');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('Nama', 'like', "%{$search}%")
                    ->orWhere('Kode', 'like', "%{$search}%");
            });
        }

        if ($request->query('status') === 'aktif') {
            $query->where('Aktif', true);
        } elseif ($request->query('status') === 'nonaktif') {
            $query->where('Aktif', false);
        }

        $data = $query->paginate(10)->withQueryString();
        $total = CalonKaryawan::count();
        $totalAktif = CalonKaryawan::where('Aktif', true)->count();
        $totalNonaktif = $total - $totalAktif;

        return view('calon-karyawan.index', compact('data', 'total', 'totalAktif', 'totalNonaktif'));
    }

    public function show(CalonKaryawan $calon)
    {
        $calon->load('dataKerabats');

        return view('calon-karyawan.show', compact('calon'));
    }

    public function create()
    {
        abort(403, 'Database shared hanya dapat dibaca dari aplikasi ini.');
    }

    public function store()
    {
        abort(403, 'Database shared hanya dapat dibaca dari aplikasi ini.');
    }

    public function edit()
    {
        abort(403, 'Database shared hanya dapat dibaca dari aplikasi ini.');
    }

    public function update()
    {
        abort(403, 'Database shared hanya dapat dibaca dari aplikasi ini.');
    }

    public function destroy()
    {
        abort(403, 'Database shared hanya dapat dibaca dari aplikasi ini.');
    }
}
