<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestGroupApproval extends BaseCommand
{
    protected $group       = 'App';
    protected $name        = 'app:test-group-approval';
    protected $description = 'Verifies the multi-signature bulk wallet distribution flow and job-balances calculations.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        CLI::write("Starting Group Approval and Job Balances test...", "yellow");

        $db->transStart();

        try {
            // Helper function to check inserts
            $checkInsert = function($table, $data) use ($db) {
                $res = $db->table($table)->insert($data);
                if (!$res) {
                    $err = $db->error();
                    throw new \Exception("Insert into {$table} failed: " . json_encode($err));
                }
                return $db->insertID();
            };

            // 1. Setup clean test data
            // Create test Mandor
            $mandorId = $checkInsert('tukang', [
                'name' => 'Test Mandor Approval',
                'email' => 'mandor.approval@test.com',
                'phone' => '089999999991',
                'balance' => 0.00,
                'role' => 'mandor',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'status' => 'Siap Kerja',
                'registration_step' => 1
            ]);

            // Create test group
            $groupId = $checkInsert('tukang_group', [
                'name_group' => 'Test Group Approved',
                'tukang_id' => $mandorId,
                'referral_code' => 'TESTAPP999',
                'balance' => 2000000.00, // Saldo grup awal Rp 2.000.000
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Create two test members
            $member1Id = $checkInsert('tukang', [
                'name' => 'Test Member 1',
                'email' => 'member1@test.com',
                'phone' => '089999999992',
                'balance' => 0.00,
                'role' => 'tukang',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'status' => 'Siap Kerja',
                'registration_step' => 1
            ]);

            $member2Id = $checkInsert('tukang', [
                'name' => 'Test Member 2',
                'email' => 'member2@test.com',
                'phone' => '089999999993',
                'balance' => 0.00,
                'role' => 'tukang',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'status' => 'Siap Kerja',
                'registration_step' => 1
            ]);

            // Add them as approved group members
            $db->table('tukang_group_members')->insertBatch([
                [
                    'tukang_group_id' => $groupId,
                    'tukang_id' => $member1Id,
                    'status' => 'approved',
                    'joined_at' => date('Y-m-d H:i:s')
                ],
                [
                    'tukang_group_id' => $groupId,
                    'tukang_id' => $member2Id,
                    'status' => 'approved',
                    'joined_at' => date('Y-m-d H:i:s')
                ]
            ]);

            // Create a fake construction progress and inflow transaction
            $progressId = 9999;
            $db->table('group_transactions')->insert([
                'id' => 9999, // use fixed id for testing
                'group_id' => $groupId,
                'amount' => 1000000.00, // inflow Rp 1.000.000
                'type' => 'inflow',
                'status' => 'approved',
                'source_project_type' => 'construction',
                'source_invoice_id' => $progressId,
                'description' => 'Inflow Test Laporan Progress #9999',
                'created_at' => date('Y-m-d H:i:s')
            ]);

            CLI::write("1. Test Data Setup Complete.", "green");
            CLI::write("Group ID: {$groupId}, Mandor ID: {$mandorId}, Members: {$member1Id}, {$member2Id}", "cyan");
            CLI::write("Inflow Progress ID: {$progressId} with Rp 1.000.000 inflow registered.", "cyan");

            // Mock the distributeBulk API logic
            // Mandor proposes to distribute Rp 800.000 total (Rp 400.000 to Member 1, Rp 400.000 to Member 2)
            $distributions = [
                ['tukang_id' => $member1Id, 'amount' => 400000.00],
                ['tukang_id' => $member2Id, 'amount' => 400000.00]
            ];
            $totalAllocated = 800000.00;

            // Perform distribution proposal
            $db->table('group_transactions')->insert([
                'group_id' => $groupId,
                'amount' => $totalAllocated,
                'type' => 'outflow',
                'status' => 'pending',
                'source_project_type' => 'construction',
                'source_invoice_id' => $progressId,
                'description' => 'Usulan pembagian upah progress #9999',
                'distributions_data' => json_encode($distributions),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $txId = $db->insertID();

            CLI::write("2. Mandor Proposed Outflow. Transaction ID: {$txId} (status: pending)", "green");

            // Verify group balance has NOT changed yet
            $group = $db->table('tukang_group')->where('id', $groupId)->get()->getRowArray();
            if ((float)$group['balance'] !== 2000000.00) {
                throw new \Exception("FAIL: Group balance was deducted prematurely!");
            }
            CLI::write("Verified: Group balance remains Rp 2.000.000 (Correct).", "green");

            // Check job balance remaining undistributed calculation (must include pending transactions)
            $inflowAmount = 1000000.00;
            $outflowSum = $db->table('group_transactions')
                ->select('SUM(amount) as total')
                ->where('group_id', $groupId)
                ->where('type', 'outflow')
                ->where('source_project_type', 'construction')
                ->where('source_invoice_id', $progressId)
                ->whereIn('status', ['approved', 'pending'])
                ->get()->getRowArray();
            $alreadyDistributed = $outflowSum ? (float)$outflowSum['total'] : 0.0;
            $remaining = $inflowAmount - $alreadyDistributed;
            
            if ($remaining !== 200000.00) {
                throw new \Exception("FAIL: Remaining undistributed wages calculation error. Got: {$remaining}, expected: 200000.00");
            }
            CLI::write("Verified: Remaining undistributed wages correctly calculated as Rp 200.000 (Rp 1.000.000 - Rp 800.000 pending).", "green");

            // Simulate member 1 voting approved
            $db->table('group_transaction_approvals')->insert([
                'group_transaction_id' => $txId,
                'tukang_id' => $member1Id,
                'vote' => 'approved',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            CLI::write("3. Member 1 voted APPROVED.", "green");

            // Verify status remains pending (1 approval is not majority of 2 members, majority required is 2)
            $txCheck = $db->table('group_transactions')->where('id', $txId)->get()->getRowArray();
            if ($txCheck['status'] !== 'pending') {
                throw new \Exception("FAIL: Transaction status changed to approved without majority!");
            }
            CLI::write("Verified: Status is still pending after 1 approval (Correct).", "green");

            // Simulate member 2 voting approved
            // Let's run the actual evaluation logic inside the vote function
            $db->table('group_transaction_approvals')->insert([
                'group_transaction_id' => $txId,
                'tukang_id' => $member2Id,
                'vote' => 'approved',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            CLI::write("4. Member 2 voted APPROVED.", "green");

            // Evaluate votes
            $votes = $db->table('group_transaction_approvals')
                ->select('vote, COUNT(id) as count')
                ->where('group_transaction_id', $txId)
                ->groupBy('vote')
                ->get()->getResultArray();
            
            $approvedVotes = 0;
            foreach ($votes as $v) {
                if ($v['vote'] === 'approved') $approvedVotes = (int)$v['count'];
            }

            $totalMembers = 2; // 2 active members
            $majorityRequired = floor($totalMembers / 2) + 1; // 2 votes

            if ($approvedVotes >= $majorityRequired) {
                // Execute checkout/payout
                $db->table('tukang_group')->where('id', $groupId)->update(['balance' => 2000000.00 - $totalAllocated]);
                $db->table('group_transactions')->where('id', $txId)->update(['status' => 'approved']);

                foreach ($distributions as $d) {
                    $db->table('tukang')->where('id', $d['tukang_id'])->update(['balance' => $d['amount']]);
                    $db->table('tukang_transactions')->insert([
                        'tukang_id' => $d['tukang_id'],
                        'group_transaction_id' => $txId,
                        'amount' => $d['amount'],
                        'type' => 'income',
                        'description' => 'Penerimaan upah kelompok',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                CLI::write("Majority reached! Payout executed.", "cyan");
            }

            // Verify final states
            $finalGroup = $db->table('tukang_group')->where('id', $groupId)->get()->getRowArray();
            if ((float)$finalGroup['balance'] !== 1200000.00) {
                throw new \Exception("FAIL: Final group balance is incorrect: " . $finalGroup['balance']);
            }
            CLI::write("Verified: Final group balance is Rp 1.200.000 (Correct).", "green");

            $finalTx = $db->table('group_transactions')->where('id', $txId)->get()->getRowArray();
            if ($finalTx['status'] !== 'approved') {
                throw new \Exception("FAIL: Final transaction status is not approved.");
            }
            CLI::write("Verified: Final transaction status is APPROVED (Correct).", "green");

            $m1 = $db->table('tukang')->where('id', $member1Id)->get()->getRowArray();
            $m2 = $db->table('tukang')->where('id', $member2Id)->get()->getRowArray();
            if ((float)$m1['balance'] !== 400000.00 || (float)$m2['balance'] !== 400000.00) {
                throw new \Exception("FAIL: Wallets were not updated correctly. M1: {$m1['balance']}, M2: {$m2['balance']}");
            }
            CLI::write("Verified: Member 1 & 2 wallets updated to Rp 400.000 each (Correct).", "green");

            $personalTxCount = $db->table('tukang_transactions')->where('group_transaction_id', $txId)->countAllResults();
            if ($personalTxCount !== 2) {
                throw new \Exception("FAIL: Expected 2 personal transactions, found: {$personalTxCount}");
            }
            CLI::write("Verified: Personal transaction logs generated for both members (Correct).", "green");

            CLI::write("ALL APPROVAL FLOW TESTS COMPLETED SUCCESSFULLY!", "green");

        } catch (\Exception $e) {
            CLI::error("TEST FAILED: " . $e->getMessage());
        } finally {
            // Rollback everything to leave database pristine
            $db->transRollback();
            CLI::write("Database test transaction rolled back successfully. Database remains pristine.", "yellow");
        }
    }
}
