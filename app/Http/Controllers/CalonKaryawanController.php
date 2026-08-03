<?php

namespace App\Http\Controllers;

use App\Models\CalonKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalonKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonKaryawan::latest();

        // Pencarian berdasarkan nama / kode
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->query('status') === 'aktif') {
            $query->where('aktif', true);
        } elseif ($request->query('status') === 'nonaktif') {
            $query->where('aktif', false);
        }

        $data = $query->paginate(10)->withQueryString();

        $total       = CalonKaryawan::count();
        $totalAktif  = CalonKaryawan::where('aktif', true)->count();
        $totalNonaktif = $total - $totalAktif;

        return view('calon-karyawan.index', compact('data', 'total', 'totalAktif', 'totalNonaktif'));
    }

    public function create()
    {
        $kodeBerikutnya = $this->generateKode();
        return view('calon-karyawan.create', compact('kodeBerikutnya'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $validated['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('foto')) {
            $validated['foto_path'] = $request->file('foto')->store('calon-karyawan', 'public');
        }

        $calon = CalonKaryawan::create($validated);

        $this->syncKerabat($calon, $request);

        return redirect()
            ->route('calon-karyawan.index')
            ->with('success', 'Data calon karyawan "' . $calon->nama . '" berhasil disimpan.');
    }

    public function show(CalonKaryawan $calon)
    {
        $calon->load('dataKerabats');
        return view('calon-karyawan.show', compact('calon'));
    }

    public function edit(CalonKaryawan $calon)
    {
        $calon->load('dataKerabats');
        return view('calon-karyawan.edit', compact('calon'));
    }

    public function update(Request $request, CalonKaryawan $calon)
    {
        $validated = $this->validateData($request, $calon->id);

        $validated['aktif'] = $request->boolean('aktif');

        // Ganti foto bila ada file baru; bila tidak, pertahankan foto lama.
        if ($request->hasFile('foto')) {
            if ($calon->foto_path) {
                Storage::disk('public')->delete($calon->foto_path);
            }
            $validated['foto_path'] = $request->file('foto')->store('calon-karyawan', 'public');
        }

        $calon->update($validated);

        $this->syncKerabat($calon, $request);

        return redirect()
            ->route('calon-karyawan.show', $calon)
            ->with('success', 'Data calon karyawan "' . $calon->nama . '" berhasil diperbarui.');
    }

    public function destroy(CalonKaryawan $calon)
    {
        if ($calon->foto_path) {
            Storage::disk('public')->delete($calon->foto_path);
        }
        $calon->delete();

        return redirect()
            ->route('calon-karyawan.index')
            ->with('success', 'Data calon karyawan "' . $calon->nama . '" berhasil dihapus.');
    }

    /**
     * Validasi bersama untuk store & update.
     * Parameter $id diabaikan bila null (untuk create).
     */
    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $kodeRule = 'required|string|max:20|unique:calon_karyawans,kode';
        if ($ignoreId) {
            $kodeRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'kode'                  => $kodeRule,
            'nama'                  => 'required|string|max:255',
            'panggilan'             => 'nullable|string|max:255',
            'no_ktp'                => 'nullable|string|max:30',
            'alamat_ktp'            => 'nullable|string',
            'kota_ktp'              => 'nullable|string|max:255',
            'gol_darah'             => 'nullable|string|max:5',
            'no_sim'                => 'nullable|string|max:30',
            'tempat_lahir'          => 'nullable|string|max:255',
            'tgl_lahir'             => 'nullable|date',
            'sex'                   => 'nullable|in:L,P',
            'agama'                 => 'nullable|string|max:255',
            'tinggi_cm'             => 'nullable|integer|min:0|max:999',
            'berat_kg'              => 'nullable|integer|min:0|max:999',
            'warga_negara'          => 'nullable|string|max:255',
            'status_nikah'          => 'nullable|string|max:255',

            'alamat'                => 'nullable|string',
            'no_telp'               => 'nullable|string|max:30',
            'email'                 => 'nullable|email|max:255',
            'status_tempat_tinggal' => 'nullable|string|max:255',
            'hobby'                 => 'nullable|string|max:255',
            'keterangan'            => 'nullable|string',

            'tgl_masuk'             => 'nullable|date',
            'tgl_resigned'          => 'nullable|date',
            'alasan_resigned'       => 'nullable|string|max:255',
            'cost_center'           => 'nullable|string|max:255',
            'posting'               => 'nullable|string|max:255',
            'aktif'                 => 'nullable|boolean',
            'awal_group_of_employee'=> 'nullable|string|max:255',
            'awal_cabang'           => 'nullable|string|max:255',
            'group_of_employee'     => 'nullable|string|max:255',
            'cuti_per_tahun'        => 'nullable|integer|min:0|max:365',
            'kategori'              => 'nullable|string|max:255',
            'sub_kategori'          => 'nullable|string|max:255',
            'divisi'                => 'nullable|string|max:255',
            'jalur_pendaftaran'     => 'nullable|string|max:255',
            'pangkat'               => 'nullable|string|max:255',
            'nrp'                   => 'nullable|string|max:30',
            'organisasi'            => 'nullable|string|max:255',
            'grup1'                 => 'nullable|string|max:255',
            'grup2'                 => 'nullable|string|max:255',
            'grup3'                 => 'nullable|string|max:255',

            'no_passport'           => 'nullable|string|max:30',
            'passport_expired'      => 'nullable|date',
            'no_visa'               => 'nullable|string|max:30',
            'no_kk'                 => 'nullable|string|max:30',
            'no_bpjs_kesehatan'     => 'nullable|string|max:30',
            'no_bpjs_tenaga_kerja'  => 'nullable|string|max:30',

            'nama_bank'             => 'nullable|string|max:255',
            'no_rekening'           => 'nullable|string|max:40',
            'atas_nama_rekening'    => 'nullable|string|max:255',
            'tipe_rekening'         => 'nullable|string|max:255',

            'password'              => 'nullable|string|max:255',
            'foto'                  => 'nullable|image|max:2048',

            'kerabat_nama.*'        => 'nullable|string|max:255',
            'kerabat_hubungan.*'    => 'nullable|string|max:255',
            'kerabat_telp.*'        => 'nullable|string|max:30',
            'kerabat_pekerjaan.*'   => 'nullable|string|max:255',
        ]);
    }

    /**
     * Simpan ulang data kerabat dari input (dipakai store & update).
     */
    private function syncKerabat(CalonKaryawan $calon, Request $request): void
    {
        if (!$request->filled('kerabat_nama')) {
            return;
        }

        $calon->dataKerabats()->delete();

        foreach ($request->input('kerabat_nama') as $i => $namaKerabat) {
            if (blank($namaKerabat)) {
                continue;
            }
            $calon->dataKerabats()->create([
                'nama'      => $namaKerabat,
                'hubungan'  => $request->input('kerabat_hubungan')[$i] ?? null,
                'no_telp'   => $request->input('kerabat_telp')[$i] ?? null,
                'pekerjaan' => $request->input('kerabat_pekerjaan')[$i] ?? null,
            ]);
        }
    }

    private function generateKode(): string
    {
        $last = CalonKaryawan::orderByDesc('id')->first();
        $next = $last ? ((int) preg_replace('/\D/', '', $last->kode)) + 1 : 1;
        return str_pad((string) $next, 5, '0', STR_PAD_LEFT);
    }
}
