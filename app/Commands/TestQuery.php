<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQuery extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'app:test-query';
    protected $description = 'Runs diagnostic checks on app queries';

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        // Cek struktur tabel ahsp
        CLI::write('=== columns of ahsp ===');
        if ($db->tableExists('ahsp')) {
            foreach ($db->query("SHOW COLUMNS FROM `ahsp`")->getResultArray() as $c) {
                CLI::write("  {$c['Field']} ({$c['Type']})");
            }
        } else {
            CLI::write('  (TABLE ahsp TIDAK ADA)');
        }

        CLI::write('');

        // Cek sample data ahsp
        CLI::write('=== Sample data ahsp (3 rows) ===');
        if ($db->tableExists('ahsp')) {
            $rows = $db->query("SELECT * FROM ahsp LIMIT 3")->getResultArray();
            foreach ($rows as $row) {
                CLI::write('  ' . json_encode($row));
            }
        }

        CLI::write('');

        // Cek apakah ada tabel ahsp_bahan atau ahsp_material
        $relatedTables = ['ahsp_bahan', 'ahsp_material', 'ahsp_alat', 'ahsp_tenaga', 'ahsp_items'];
        foreach ($relatedTables as $t) {
            if ($db->tableExists($t)) {
                CLI::write("=== TABLE {$t} ADA ===");
                foreach ($db->query("SHOW COLUMNS FROM `{$t}`")->getResultArray() as $c) {
                    CLI::write("  {$c['Field']} ({$c['Type']})");
                }
                CLI::write('');
            }
        }
    }
}
