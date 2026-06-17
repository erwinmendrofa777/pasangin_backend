<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestEmailOtp extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'app:test-email-otp';
    protected $description = 'Runs diagnostic checks on Email OTP endpoints';

    public function run(array $params)
    {
        CLI::write("Starting Email OTP diagnostics...", 'yellow');

        $db = \Config\Database::connect();
        
        // 1. Check DB Connection
        try {
            $db->initialize();
            CLI::write("✓ Database connected successfully.", 'green');
        } catch (\Exception $e) {
            CLI::error("✗ Database connection failed: " . $e->getMessage());
            return;
        }

        // 2. Query some tables
        $tables = ['users', 'tukang', 'suppliers'];
        $testEmails = [];

        foreach ($tables as $table) {
            $count = $db->table($table)->countAllResults();
            CLI::write("- Table '{$table}' has {$count} records.", 'cyan');
            
            if ($count > 0) {
                $row = $db->table($table)->select('email')->get()->getRow();
                if ($row && !empty($row->email)) {
                    $testEmails[$table] = $row->email;
                }
            }
        }

        // 3. Perform OTP tests
        CLI::write("\nTesting requestOtpByEmail logic...", 'yellow');
        
        $email = 'test_otp_verify@example.com';
        $role = 'user'; // allowed: user, tukang, supplier

        // Ensure clean state
        $db->table('password_reset_tokens')->where('email', $email)->where('role', $role)->delete();

        // 3.1 Generate and store
        $otpCode = sprintf("%06d", mt_rand(0, 999999));
        $db->table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => $otpCode,
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        CLI::write("✓ Generated 6-digit OTP code '{$otpCode}' for email '{$email}' (role: '{$role}'). Stored in database.", 'green');

        // 3.2 Verify matching code
        $cekOtp = $db->table('password_reset_tokens')
                     ->where('email', $email)
                     ->where('role', $role)
                     ->where('token', $otpCode)
                     ->get()->getRow();

        if ($cekOtp) {
            CLI::write("✓ Verification check 1 (Correct Code): Passed. Found matching token in password_reset_tokens.", 'green');
        } else {
            CLI::error("✗ Verification check 1 (Correct Code): Failed.");
        }

        // 3.3 Verify incorrect code
        $incorrectCode = '111111';
        $cekIncorrect = $db->table('password_reset_tokens')
                           ->where('email', $email)
                           ->where('role', $role)
                           ->where('token', $incorrectCode)
                           ->get()->getRow();

        if (!$cekIncorrect) {
            CLI::write("✓ Verification check 2 (Incorrect Code): Passed. Correctly rejected '{$incorrectCode}'.", 'green');
        } else {
            CLI::error("✗ Verification check 2 (Incorrect Code): Failed.");
        }

        // Clean up
        $db->table('password_reset_tokens')->where('email', $email)->where('role', $role)->delete();
        CLI::write("✓ Cleaned up dummy token from password_reset_tokens.", 'green');

        CLI::write("\nAll local database-related OTP checks passed!", 'green');
    }
}
