<?php

namespace App\Http\Controllers;

use App\Models\CalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $kodeBerikutnya = $this->generateKode();

        return view('calon-karyawan.create', compact('kodeBerikutnya'));
    }

    public function store(Request $request)
    {
        // Kolom yang AMAN ditulis ke tabel perusahaan (CalonEmployee).
        // Jangan tambah kolom lain sebelum struktur tabel dikonfirmasi pembimbing.
        $validated = $request->validate([
            'kode'  => 'required|string|max:30',
            'nama'  => 'required|string|max:50',
            'no_hp' => 'required|string|max:100',
            'aktif' => 'nullable|boolean',
        ]);

        $duplicate = CalonKaryawan::where('Kode', $validated['kode'])->exists();
        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['kode' => 'Kode "' . $validated['kode'] . '" sudah dipakai di database.']);
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                CalonKaryawan::create([
                    'Kode'     => $validated['kode'],
                    'Nama'     => $validated['nama'],
                    'NoHP'     => $validated['no_hp'],
                    'TglEntry' => now(),
                    'Aktif'    => $request->boolean('aktif', true),
                ]);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['db' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('calon-karyawan.index')
            ->with('success', 'Data calon karyawan "' . $validated['nama'] . '" berhasil disimpan.');
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

    /**
     * Kode berikutnya = kode numerik terbesar + 1, format 5 digit.
     * Bila database tidak bisa diakses, fallback ke kode berbasis waktu agar form tetap bisa dibuka.
     */
    private function generateKode(): string
    {
        try {
            $last = CalonKaryawan::orderByDesc('Kode')->first();
            $next = $last ? ((int) preg_replace('/\D/', '', $last->Kode)) + 1 : 1;

            return str_pad((string) $next, 5, '0', STR_PAD_LEFT);
        } catch (\Throwable $e) {
            return 'C' . date('ymdHis');
        }
    }
}
