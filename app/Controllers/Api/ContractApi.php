<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ContractApi extends ResourceController
{
    use ResponseTrait;
    protected $db;
    protected $format    = 'json';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function construction_contract($id)
    {
        $data = $this->db->table('construction_requests cr')
        ->select('
            cr.id,
            cr.address,
            cr.rab_file,
            u.full_name,
            u.phone_number,
            u.address as user_address,
            r.updated_at as contract_date,
            r.is_locked
        ')
        ->join('users u', 'u.id = cr.user_id', 'left')
        ->join('construction_rabs r', 'r.construction_id = cr.id', 'left')
        ->where('cr.id', $id)
        ->get()
        ->getRowArray();

    if (!$data) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ]);
    }

    // 🔹 2. Ambil work items
    $workItems = $this->db->table('construction_rabs')
        ->select('roman_number, group_name, SUM(total_price) as total_price')
        ->where('construction_id', $id)
        ->groupBy(['roman_number', 'group_name'])
        ->orderBy('roman_number', 'ASC')
        ->get()
        ->getResultArray();

    // 🔹 3. Hitung total
    $grandTotal = array_sum(array_column($workItems, 'total_price'));

    //image_url
    $file_url = base_url('uploads/surat_kontrak/' . $data['rab_file']);

    // 🔹 4. Mapping response
    $response = [
        'data' => [
            'contract_number' => 'KTR-' . date('Y') . '-' . $id,

            'status' => ($data['is_locked'] == 1) ? 'complete' : 'pending',

            'client_name' => $data['full_name'],
            'client_phone' => $data['phone_number'],
            'client_address' => $data['user_address'],

            'file_url' => $file_url,

            'project_name' => 'Projek ' . $id,
            'project_location' => $data['address'],

            'contract_date' => $data['contract_date']
                ? date('c', strtotime($data['contract_date']))
                : null,

            'grand_total' => $grandTotal,
            'total_pekerjaan' => count($workItems),

            'work_items' => $workItems
        ]
    ];

    return $this->response->setJSON($response);
    }

    public function renovation_contract($id)
    {
        $data = $this->db->table('renovation_requests rr')
        ->select('
            rr.id,
            rr.address,
            rr.rab_file,
            u.full_name,
            u.phone_number,
            u.address as user_address,
            r.updated_at as contract_date,
            r.is_locked
        ')
        ->join('users u', 'u.id = rr.user_id', 'left')
        ->join('renovation_rabs r', 'r.renovation_id = rr.id', 'left')
        ->where('rr.id', $id)
        ->get()
        ->getRowArray();

    if (!$data) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Data tidak ditemukan'
        ]);
    }

    // 🔹 2. Ambil work items
    $workItems = $this->db->table('renovation_rabs')
        ->select('roman_number, group_name, SUM(total_price) as total_price')
        ->where('renovation_id', $id)
        ->groupBy(['roman_number', 'group_name'])
        ->orderBy('roman_number', 'ASC')
        ->get()
        ->getResultArray();

    // 🔹 3. Hitung total
    $grandTotal = array_sum(array_column($workItems, 'total_price'));

    //image_url
    $file_url = base_url('uploads/surat_kontrak/' . $data['rab_file']);

    // 🔹 4. Mapping response
    $response = [
        'data' => [
            'contract_number' => 'KTR-' . date('Y') . '-' . $id,

            'status' => ($data['is_locked'] == 1) ? 'complete' : 'pending',

            'client_name' => $data['full_name'],
            'client_phone' => $data['phone_number'],
            'client_address' => $data['user_address'],

            'file_url' => $file_url,

            'project_name' => 'Projek ' . $id,
            'project_location' => $data['address'],

            'contract_date' => $data['contract_date']
                ? date('c', strtotime($data['contract_date']))
                : null,

            'grand_total' => $grandTotal,
            'total_pekerjaan' => count($workItems),

            'work_items' => $workItems
        ]
    ];

    return $this->response->setJSON($response);
    }
}