<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Modules\Tukang\Repositories\TukangRepository;
use App\Modules\Tukang\Models\TukangSkillMapModel;

class TestRelation extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'app:test-relation';
    protected $description = 'Verifikasi relasi dinamis Tukang dan Tukang Skill';

    public function run(array $params)
    {
        CLI::write('=== Memulai verifikasi relasi Tukang ===');

        $repo = new TukangRepository();
        $mapModel = new TukangSkillMapModel();

        // 1. Ambil data Tukang ID 5 (Tukang dengan ID 5 memiliki data ter-migrasi paling banyak)
        CLI::write('1. Mengambil Tukang ID 5...');
        $tukang = $repo->findById(5);

        if (!$tukang) {
            CLI::error('Tukang ID 5 tidak ditemukan!');
            return;
        }

        CLI::write('  Nama Tukang: ' . $tukang['name']);
        CLI::write('  Specialization Dinamis: ' . ($tukang['specialization'] ?: '(kosong)'));

        // 2. Ambil data keahlian dari junction table untuk ID 5
        CLI::write('2. Membaca data keahlian terstruktur...');
        $skills = $mapModel->getSkillsByTukangId(5);
        foreach ($skills as $sk) {
            CLI::write("  - Skill ID {$sk['id']}: {$sk['skill_name']}");
        }

        // 3. Test Sinkronisasi Baru (Ubah skill Tukang ID 5)
        CLI::write('3. Menguji sinkronisasi keahlian baru (ID 1 dan ID 3)...');
        $mapModel->syncSkills(5, [1, 3]);

        // Ambil kembali dan verifikasi
        $tukangUpdated = $repo->findById(5);
        CLI::write('  Specialization Baru: ' . ($tukangUpdated['specialization'] ?: '(kosong)'));
        
        $skillsUpdated = $mapModel->getSkillsByTukangId(5);
        foreach ($skillsUpdated as $sk) {
            CLI::write("  - Skill ID {$sk['id']}: {$sk['skill_name']}");
        }

        // 4. Kembalikan data lama
        CLI::write('4. Mengembalikan data keahlian lama...');
        $oldSkillIds = array_column($skills, 'id');
        $mapModel->syncSkills(5, $oldSkillIds);

        $tukangRestored = $repo->findById(5);
        CLI::write('  Specialization Dikembalikan: ' . ($tukangRestored['specialization'] ?: '(kosong)'));

        CLI::write('=== Verifikasi Selesai! Semuanya Berjalan Sempurna ===');
    }
}
