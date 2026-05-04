<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MasterKpsp;
use App\Models\MasterTdd;
use App\Models\Tahapan;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data_perkembangan.json'));
        $data = json_decode($json, true);

        $tahapansData = [];

        foreach ($data as $row) {
            $kategori = $row['Kategori'] ?? '';
            $usia = (string) ($row['Usia_Bulan'] ?? '');
            $subKategori = $row['Sub_Kategori'] ?? '';
            $teks = $row['Pertanyaan_Atau_Keterangan'] ?? '';

            if ($kategori === 'KPSP') {
                MasterKpsp::create([
                    'umur_bulan' => (int) $usia,
                    'sub_kategori' => $subKategori,
                    'pertanyaan' => $teks,
                ]);
            } elseif ($kategori === 'TDD') {
                $usia = str_replace('–', '-', $usia); // handle en-dash
                $parts = explode('-', $usia);
                $min = (int) ($parts[0] ?? 0);
                $max = (int) ($parts[1] ?? $min);

                MasterTdd::create([
                    'umur_min' => $min,
                    'umur_max' => $max,
                    'pertanyaan' => $teks,
                ]);
            } elseif ($kategori === 'TAHAPAN') {
                if ($usia === '46145') $usia = '3-5';
                if ($usia === '46240') $usia = '6-8';
                if ($usia === '46335') $usia = '9-11';
                
                $usia = str_replace('–', '-', $usia);
                $parts = explode('-', $usia);
                $min = (int) ($parts[0] ?? 0);
                $max = (int) ($parts[1] ?? $min);
                $key = $min . '_' . $max;

                if (!isset($tahapansData[$key])) {
                    $tahapansData[$key] = [
                        'umur_min' => $min,
                        'umur_max' => $max,
                        'perkembangan' => null,
                        'stimulasi' => null,
                        'red_flags' => null,
                    ];
                }

                if (strcasecmp($subKategori, 'Perkembangan') === 0) {
                    $tahapansData[$key]['perkembangan'] = $teks;
                } elseif (strcasecmp($subKategori, 'Stimulasi') === 0) {
                    $tahapansData[$key]['stimulasi'] = $teks;
                } elseif (strcasecmp($subKategori, 'Red Flags') === 0 || strcasecmp($subKategori, 'Red Flag') === 0) {
                    $tahapansData[$key]['red_flags'] = $teks;
                }
            }
        }

        foreach ($tahapansData as $t) {
            Tahapan::create($t);
        }

        // Create Admin and Nakes
        \App\Models\User::create([
            'name' => 'Admin System',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'Admin'
        ]);

        \App\Models\User::create([
            'name' => 'Nakes Posyandu',
            'email' => 'nakes@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'Nakes'
        ]);
    }
}
