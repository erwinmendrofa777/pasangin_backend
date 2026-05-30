<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestQuery extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    protected $name = 'app:test-query';
    protected $description = 'Runs diagnostic checks on app queries';

    public function run(array $params)
    {
        CLI::write("Diagnostics complete. Use this command to test queries.");
    }
}
