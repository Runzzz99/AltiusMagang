<?php

namespace Tests\Feature;

use App\Models\CalonKaryawan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalonKaryawanTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_calon_karyawan_list(): void
    {
        CalonKaryawan::create([
            'kode' => '00001',
            'nama' => 'Budi',
            'no_hp' => 'HP00001',
            'nrp' => 'NRP00001',
            'tgl_masuk' => now(),
            'aktif' => true,
        ]);

        $this->get('/hrd/calon-karyawan')
            ->assertOk()
            ->assertSee('Budi');
    }

    public function test_store_creates_new_calon(): void
    {
        $this->post('/hrd/calon-karyawan', [
            'kode' => '00002',
            'nama' => 'Siti',
            'tempat_lahir' => 'Sby',
            'tgl_lahir' => '2000-01-01',
            'no_hp' => '0812',
            'aktif' => '1',
        ])->assertRedirect(route('calon-karyawan.index'));

        $this->assertDatabaseHas('calon_karyawans', ['kode' => '00002', 'nama' => 'Siti']);
    }
}
