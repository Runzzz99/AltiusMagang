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
        $validated = $this->validateForm($request, true);

        $duplicate = CalonKaryawan::where('Kode', $validated['kode'])->exists();
        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors(['kode' => 'Kode "' . $validated['kode'] . '" sudah dipakai di database.']);
        }

        try {
            DB::transaction(function () use ($validated, $request) {
                $payload = $this->buildPayload($validated, $request);
                $payload['Kode'] = $validated['kode'];
                $payload['TglEntry'] = now();
                if (empty($payload['no_hp'])) {
                    $payload['no_hp'] = 'HP' . $validated['kode'];
                }
                if (empty($payload['nrp'])) {
                    $payload['nrp'] = 'NRP' . $validated['kode'];
                }
                $payload['tempat_lahir'] = ($payload['tempat_lahir'] ?? '') ?: '';
                $payload['tgl_lahir'] = ($payload['tgl_lahir'] ?? '') ?: now();
                $payload['cuti_per_tahun'] = ($payload['cuti_per_tahun'] ?? 12) ?: 12;

                CalonKaryawan::create($payload);
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
        $validated = $this->validateForm($request, false);

        try {
            DB::transaction(function () use ($validated, $request, $calon) {
                $payload = $this->buildPayload($validated, $request, $calon);
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

    private function validateForm(Request $request, bool $isStore): array
    {
        $rules = [
            'nama'                  => 'required|string|max:50',
            'panggilan'             => 'nullable|string|max:50',
            'tempat_lahir'          => 'nullable|string|max:20',
            'tgl_lahir'             => 'nullable|date',
            'sex'                   => 'nullable|string|max:1',
            'agama'                 => 'nullable|string|max:20',
            'status_nikah'          => 'nullable|string|max:20',
            'warga_negara'          => 'nullable|string|max:20',
            'gol_darah'             => 'nullable|string|max:5',
            'tinggi_cm'             => 'nullable|numeric',
            'berat_kg'              => 'nullable|numeric',
            'no_ktp'                => 'nullable|string|max:100',
            'alamat_ktp'            => 'nullable|string|max:255',
            'kota_ktp'              => 'nullable|string|max:20',
            'no_sim'                => 'nullable|string|max:50',
            'alamat'                => 'nullable|string|max:200',
            'no_hp'                 => 'nullable|string|max:100',
            'email'                 => 'nullable|email|max:255',
            'status_tempat_tinggal' => 'nullable|string|max:20',
            'hobby'                 => 'nullable|string|max:50',
            'keterangan'            => 'nullable|string|max:255',
            'tgl_masuk'             => 'nullable|date',
            'awal_cabang'           => 'nullable|string|max:8',
            'group_of_employee'     => 'nullable|string|max:30',
            'awal_group_of_employee'=> 'nullable|string|max:30',
            'divisi'                => 'nullable|string|max:10',
            'pangkat'               => 'nullable|string|max:10',
            'kategori'              => 'nullable|string|max:10',
            'sub_kategori'          => 'nullable|string|max:10',
            'jalur_pendaftaran'     => 'nullable|string|max:10',
            'nrp'                   => 'nullable|string|max:50',
            'cost_center'           => 'nullable|string|max:30',
            'posting'               => 'nullable|string|max:30',
            'cuti_per_tahun'        => 'nullable|integer',
            'organisasi'            => 'nullable|string|max:10',
            'grup1'                 => 'nullable|string|max:50',
            'grup2'                 => 'nullable|string|max:50',
            'grup3'                 => 'nullable|string|max:50',
            'no_kk'                 => 'nullable|string|max:100',
            'no_bpjs_kesehatan'     => 'nullable|string|max:100',
            'no_bpjs_tenaga_kerja'  => 'nullable|string|max:100',
            'no_passport'           => 'nullable|string|max:50',
            'passport_expired'      => 'nullable|date',
            'no_visa'               => 'nullable|string|max:50',
            'nama_bank'             => 'nullable|string|max:50',
            'no_rekening'           => 'nullable|string|max:50',
            'atas_nama_rekening'    => 'nullable|string|max:100',
            'tipe_rekening'         => 'nullable|string|max:50',
            'password'              => 'nullable|string|max:100',
            'aktif'                 => 'nullable|boolean',
            'foto'                  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($isStore) {
            $rules['kode'] = 'required|string|max:30';
        }

        return $request->validate($rules);
    }

    private function buildPayload(array $v, Request $request, ?CalonKaryawan $calon = null): array
    {
        $fields = [
            'nama','panggilan','tempat_lahir','tgl_lahir','sex','agama',
            'status_nikah','warga_negara','gol_darah','tinggi_cm','berat_kg',
            'no_ktp','alamat_ktp','kota_ktp','no_sim','alamat','no_hp','email',
            'status_tempat_tinggal','hobby','keterangan','tgl_masuk','awal_cabang',
            'group_of_employee','awal_group_of_employee','divisi','pangkat',
            'kategori','sub_kategori','jalur_pendaftaran','nrp','cost_center',
            'posting','cuti_per_tahun','organisasi','grup1','grup2','grup3',
            'no_kk','no_bpjs_kesehatan','no_bpjs_tenaga_kerja','no_passport',
            'passport_expired','no_visa','nama_bank','no_rekening',
            'atas_nama_rekening','tipe_rekening',
        ];

        $payload = [];
        foreach ($fields as $f) {
            if (array_key_exists($f, $v)) {
                $payload[$f] = $v[$f];
            }
        }

        if (!empty($v['password'])) {
            $payload['password'] = $v['password'];
        }

        $payload['aktif'] = $request->boolean('aktif', $calon?->aktif ?? true);

        foreach (['divisi' => 'EmployeeDivisi', 'pangkat' => 'EmployeePangkat', 'kategori' => 'EmployeeKategori', 'sub_kategori' => 'EmployeeSubKategori', 'jalur_pendaftaran' => 'EmployeeJalurPendaftaran', 'group_of_employee' => 'GroupOfEmployee', 'awal_group_of_employee' => 'GroupOfEmployee', 'organisasi' => 'EmployeeOrg'] as $f => $table) {
            if (array_key_exists($f, $payload)) {
                $payload[$f] = $this->resolveFk($table, $payload[$f]);
            }
        }

        if ($request->hasFile('foto')) {
            $payload['foto_path'] = $request->file('foto')->store('calon-karyawan', 'public');
        }

        return $payload;
    }

    private function resolveFk(string $table, ?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        // Form accepts reference code or label. Keep raw value when legacy lookup has no row.
        return DB::table($table)
            ->where('Kode', $value)
            ->orWhere('Keterangan', $value)
            ->value('Kode') ?: $value;
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
