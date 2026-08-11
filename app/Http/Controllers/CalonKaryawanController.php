<?php

namespace App\Http\Controllers;

use App\Models\CalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'kode'         => 'required|string|max:30',
            'nama'         => 'required|string|max:50',
            'tempat_lahir' => 'nullable|string|max:20',
            'tgl_lahir'    => 'nullable|date',
            'no_hp'        => 'nullable|string|max:100',
            'aktif'        => 'nullable|boolean',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $duplicate = CalonKaryawan::where('Kode', $validated['kode'])->exists();
        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['kode' => 'Kode "' . $validated['kode'] . '" sudah dipakai di database.']);
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                $fotoPath = null;
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('calon-karyawan', 'public');
                }

                CalonKaryawan::create([
                    'Kode'         => $validated['kode'],
                    'Nama'         => $validated['nama'],
                    'NoHP'         => $validated['no_hp'] ?: ('HP' . $validated['kode']),
                    'TempatLahir'  => $validated['tempat_lahir'] ?? '',
                    'TglLahir'     => $validated['tgl_lahir'] ?? now(),
                    'NRP'          => 'NRP' . $validated['kode'],
                    'TglEntry'     => now(),
                    'Aktif'        => $request->boolean('aktif', true),
                    'FileFoto'     => $fotoPath,
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

    public function edit(CalonKaryawan $calon)
    {
        $calon->load('dataKerabats');

        return view('calon-karyawan.edit', compact('calon'));
    }

    public function update(Request $request, CalonKaryawan $calon)
    {
        $validated = $request->validate([
            'nama'         => 'required|string|max:50',
            'tempat_lahir' => 'nullable|string|max:20',
            'tgl_lahir'    => 'nullable|date',
            'no_hp'        => 'nullable|string|max:100',
            'aktif'        => 'nullable|boolean',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $calon) {
                $payload = [
                    'Nama'        => $validated['nama'],
                    'NoHP'        => $validated['no_hp'] ?: $calon->NoHP,
                    'TempatLahir' => $validated['tempat_lahir'] ?? $calon->TempatLahir,
                    'TglLahir'    => $validated['tgl_lahir'] ?? $calon->TglLahir,
                    'Aktif'       => $request->boolean('aktif', $calon->aktif),
                ];

                if ($request->hasFile('foto')) {
                    $payload['FileFoto'] = $request->file('foto')->store('calon-karyawan', 'public');
                }

                $calon->update($payload);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors(['db' => 'Gagal memperbarui: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('calon-karyawan.show', $calon)
            ->with('success', 'Data calon karyawan "' . $validated['nama'] . '" berhasil diperbarui.');
    }

    public function destroy(CalonKaryawan $calon)
    {
        try {
            DB::transaction(function () use ($calon) {
                // Hapus file foto lokal bila ada supaya tidak menumpuk
                if ($calon->FileFoto) {
                    Storage::disk('public')->delete($calon->FileFoto);
                }
                $calon->delete();
            });
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withErrors(['db' => 'Gagal menghapus: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('calon-karyawan.index')
            ->with('success', 'Data calon karyawan "' . $calon->nama . '" berhasil dihapus.');
    }

    /**
     * Serve the uploaded photo of a candidate.
     *
     * Foto disimpan di disk lokal machine yang meng-upload (storage/app/public).
     * Pada arsitektur database-bersama via VPN, file fisik tidak tersedia
     * di machine lain sehingga <img src="asset(storage/...)"> broken.
     * Route ini mengembalikan stream file ketika ada, dan avatar SVG
     * berinisial nama bila file tidak ditemukan — sehingga tampilan
     * konsisten di semua machine.
     */
    public function foto(CalonKaryawan $calon)
    {
        $path = $calon->FileFoto;

        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->response($path);
        }

        // Fallback: avatar SVG berinisial
        $inisial = strtoupper(mb_substr($calon->Nama ?? $calon->Kode ?? '?', 0, 1));
        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" rx="100" fill="#e0e7ff"/>
  <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle"
        font-family="ui-sans-serif, sans-serif" font-size="80" font-weight="700" fill="#4338ca">{$inisial}</text>
</svg>
SVG;
        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

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
