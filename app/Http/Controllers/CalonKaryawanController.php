<?php

namespace App\Http\Controllers;

use App\Models\CalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalonKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonKaryawan::orderByDesc('tgl_masuk');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        if ($request->query('status') === 'aktif') {
            $query->where('aktif', true);
        } elseif ($request->query('status') === 'nonaktif') {
            $query->where('aktif', false);
        }

        $data = $query->paginate(10)->withQueryString();
        $total = CalonKaryawan::count();
        $totalAktif = CalonKaryawan::where('aktif', true)->count();
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
            'kode'         => 'required|string|max:30',
            'nama'         => 'required|string|max:50',
            'tempat_lahir' => 'nullable|string|max:20',
            'tgl_lahir'    => 'nullable|date',
            'no_hp'        => 'nullable|string|max:100',
            'aktif'        => 'nullable|boolean',
        ]);

        $duplicate = CalonKaryawan::where('kode', $validated['kode'])->exists();
        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['kode' => 'Kode "' . $validated['kode'] . '" sudah dipakai di database.']);
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                CalonKaryawan::create([
'kode'         => $validated['kode'],
                    'nama'         => $validated['nama'],
                    'no_hp'        => $validated['no_hp'] ?: ('HP' . $validated['kode']), // UNIQUE constraint: NoHP harus unik per baris. ponytail: ganti ke field NoHP asli bila pembimbing sediakan.
                    'tempat_lahir' => $validated['tempat_lahir'] ?? '',
                    'tgl_lahir'    => $validated['tgl_lahir'] ?? now(),
                    'nrp'          => 'NRP' . $validated['kode'], // UNIQUE constraint: harus unik per baris. ponytail: ganti ke field NRP asli di form bila pembimbing sediakan.
                    'tgl_masuk'    => now(),
                    'aktif'        => $request->boolean('aktif', true),
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
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $calon) {
                $calon->update([
'nama'         => $validated['nama'],
                    'no_hp'        => $validated['no_hp'] ?: $calon->no_hp,
                    'tempat_lahir' => $validated['tempat_lahir'] ?? $calon->tempat_lahir,
                    'tgl_lahir'    => $validated['tgl_lahir'] ?? $calon->tgl_lahir,
                    'aktif'        => $request->boolean('aktif', $calon->aktif),
                ]);
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
     * Kode berikutnya = kode numerik terbesar + 1, format 5 digit.
     * Bila database tidak bisa diakses, fallback ke kode berbasis waktu agar form tetap bisa dibuka.
     */
    private function generateKode(): string
    {
        try {
            $last = CalonKaryawan::orderByDesc('kode')->first();
            $next = $last ? ((int) preg_replace('/\D/', '', $last->kode)) + 1 : 1;

            return str_pad((string) $next, 5, '0', STR_PAD_LEFT);
        } catch (\Throwable $e) {
            return 'C' . date('ymdHis');
        }
    }
}
