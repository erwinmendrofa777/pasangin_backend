<?php

namespace App\Modules\Tukang\Repositories;

use App\Modules\Tukang\Models\TukangModel;
use App\Modules\Tukang\Repositories\Contracts\TukangRepositoryInterface;

class TukangRepository implements TukangRepositoryInterface
{
    protected TukangModel $model;

    public function __construct()
    {
        $this->model = new TukangModel();
    }

    public function findById(int $id): ?array
    {
        $tukang = $this->model->find($id) ?: null;
        return $this->populateSpecialization($tukang);
    }

    public function findWithFcmToken(): array
    {
        return $this->model->db->table('user_fcm_tokens')
            ->select('user_id as id, fcm_token')
            ->where('user_type', 'tukang')
            ->get()
            ->getResultArray();
    }

    public function findAllWithRatings(): array
    {
        $tukangs = $this->model->db
            ->table('tukang')
            ->select([
                'tukang.*',
                'COALESCE((SELECT ROUND(AVG(skill_score), 1) FROM tukang_rating WHERE tukang_rating.id_tukang = tukang.id), 0) as skill_score',
                'COALESCE((SELECT ROUND(AVG(behavior_score), 1) FROM tukang_rating WHERE tukang_rating.id_tukang = tukang.id), 0) as behavior_score',
                'COALESCE(tukang.rata_rata_rating, 0) as rata_rata_rating',
                'COALESCE((SELECT name_group FROM tukang_group WHERE tukang_id = tukang.id LIMIT 1), (SELECT tg.name_group FROM tukang_group tg JOIN tukang_group_members tgm ON tgm.tukang_group_id = tg.id WHERE tgm.tukang_id = tukang.id LIMIT 1)) AS group_name',
                'COALESCE((SELECT referral_code FROM tukang_group WHERE tukang_id = tukang.id LIMIT 1), (SELECT tg.referral_code FROM tukang_group tg JOIN tukang_group_members tgm ON tgm.tukang_group_id = tg.id WHERE tgm.tukang_id = tukang.id LIMIT 1)) AS group_referral_code',
                'CASE WHEN tukang.role = \'mandor\' AND (SELECT id FROM tukang_group WHERE tukang_id = tukang.id LIMIT 1) IS NOT NULL THEN \'owner\' ELSE (SELECT status FROM tukang_group_members WHERE tukang_id = tukang.id LIMIT 1) END AS group_status'
            ])
            ->orderBy('tukang.id', 'DESC')
            ->get()
            ->getResultArray();

        return $this->populateSpecializations($tukangs);
    }

    public function countAll(): int
    {
        return $this->model->countAllResults();
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }

    public function findAllOrderedByName(): array
    {
        $result = $this->model
            ->orderBy('name', 'ASC')
            ->findAll();

        $tukangs = is_array($result) ? $result : [];
        return $this->populateSpecializations($tukangs);
    }

    public function save(array $data): bool
    {
        return (bool) $this->model->save($data);
    }

    public function getInsertID(): int
    {
        return (int) $this->model->getInsertID();
    }

    private function populateSpecialization(?array $tukang): ?array
    {
        if (!$tukang) {
            return null;
        }

        $skills = $this->model->db->table('tukang_skill_map m')
            ->select('s.skill_name')
            ->join('tukang_skill s', 's.id = m.tukang_skill_id')
            ->where('m.tukang_id', $tukang['id'])
            ->get()
            ->getResultArray();

        $names = array_column($skills, 'skill_name');
        $tukang['specialization'] = implode(', ', $names);
        return $tukang;
    }

    private function populateSpecializations(array $tukangs): array
    {
        if (empty($tukangs)) {
            return [];
        }

        $ids = array_column($tukangs, 'id');
        $skills = $this->model->db->table('tukang_skill_map m')
            ->select('m.tukang_id, s.skill_name')
            ->join('tukang_skill s', 's.id = m.tukang_skill_id')
            ->whereIn('m.tukang_id', $ids)
            ->get()
            ->getResultArray();

        $skillsByTukang = [];
        foreach ($skills as $s) {
            $skillsByTukang[$s['tukang_id']][] = $s['skill_name'];
        }

        foreach ($tukangs as &$t) {
            $t['specialization'] = isset($skillsByTukang[$t['id']]) ? implode(', ', $skillsByTukang[$t['id']]) : '';
        }

        return $tukangs;
    }

    public function searchForDropdown(string $term): array
    {
        $builder = $this->model->builder();
        $builder->select('id, name, phone');
                
        if (!empty($term)) {
            $builder->groupStart()
                    ->like('name', $term)
                    ->orLike('phone', $term)
                    ->groupEnd();
        }
        
        $query = $builder->limit(20)->get()->getResultArray();
        
        $results = [];
        foreach ($query as $row) {
            $results[] = ['id' => $row['id'], 'text' => $row['name'] . ' (' . $row['phone'] . ')'];
        }
        
        return $results;
    }

    public function findGroupConstructionTargets(): array
    {
        return $this->model->db->query("
            SELECT 
                ct.id,
                ct.construction_id,
                creq.address as project_address,
                creq.start_date,
                creq.workday,
                COALESCE(ahsp.uraian, ca.activity_name) as activity_name,
                COALESCE(crab.volume, ca.volume) as volume,
                COALESCE(crab.unit, ca.unit) as unit,
                ct.start_week,
                ct.end_week,
                ct.status,
                ja.tukang_id,
                COALESCE(
                    (SELECT name_group FROM tukang_group WHERE tukang_id = ja.tukang_id LIMIT 1),
                    (SELECT tg.name_group FROM tukang_group tg JOIN tukang_group_members tgm ON tgm.tukang_group_id = tg.id WHERE tgm.tukang_id = ja.tukang_id AND tgm.status = 'approved' LIMIT 1)
                ) as group_name
            FROM construction_targets ct
            JOIN job_applications ja ON ja.id = ct.id_job_applications
            JOIN construction_requests creq ON creq.id = ct.construction_id
            LEFT JOIN rabs crab ON crab.id = ct.id_construction_rabs
            LEFT JOIN ahsp ON ahsp.id = crab.ahsp_id
            LEFT JOIN construction_addendum ca ON ca.id = ct.id_construction_addendum
            ORDER BY ct.construction_id DESC, ct.start_week ASC
        ")->getResultArray();
    }

    public function findGroupTransactionsWithApprovals(): array
    {
        $db = $this->model->db;

        // 1. Ambil semua transaksi kelompok
        $transactions = $db->query("
            SELECT
                gt.id,
                gt.group_id,
                tg.name_group,
                t.name  AS mandor_name,
                t.id    AS mandor_id,
                gt.amount,
                gt.type,
                gt.status,
                gt.source_project_type,
                gt.source_invoice_id,
                gt.description,
                gt.distributions_data,
                gt.created_at,
                (
                    SELECT COUNT(*)
                    FROM group_transaction_approvals
                    WHERE group_transaction_id = gt.id AND vote = 'approved'
                ) AS total_approved,
                (
                    SELECT COUNT(*)
                    FROM group_transaction_approvals
                    WHERE group_transaction_id = gt.id AND vote = 'rejected'
                ) AS total_rejected,
                (
                    SELECT COUNT(*) + 1
                    FROM tukang_group_members
                    WHERE tukang_group_id = gt.group_id
                ) AS total_members
            FROM group_transactions gt
            JOIN tukang_group tg ON tg.id = gt.group_id
            JOIN tukang t ON t.id = tg.tukang_id
            ORDER BY gt.created_at DESC
        ")->getResultArray();

        if (empty($transactions)) {
            return [];
        }

        // 2. Ambil semua votes berdasarkan ID transaksi yang ditemukan
        $ids = array_column($transactions, 'id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $approvals = $db->query("
            SELECT
                gta.group_transaction_id,
                gta.tukang_id,
                gta.vote,
                gta.created_at AS voted_at,
                t.name         AS voter_name,
                t.role         AS voter_role
            FROM group_transaction_approvals gta
            JOIN tukang t ON t.id = gta.tukang_id
            WHERE gta.group_transaction_id IN ($placeholders)
        ", $ids)->getResultArray();

        // 3. Kelompokkan approvals per transaksi
        $approvalsByTx = [];
        foreach ($approvals as $a) {
            $approvalsByTx[$a['group_transaction_id']][] = $a;
        }

        // 4. Attach approvals ke setiap transaksi
        foreach ($transactions as &$tx) {
            $tx['approvals'] = $approvalsByTx[$tx['id']] ?? [];

            // Decode distributions_data jika ada
            if (!empty($tx['distributions_data'])) {
                $decoded = json_decode($tx['distributions_data'], true);
                $tx['distributions'] = is_array($decoded) ? $decoded : [];
            } else {
                $tx['distributions'] = [];
            }
        }
        unset($tx);

        return $transactions;
    }
}

