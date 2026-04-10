<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class RenovationApi extends BaseController
{
    use ResponseTrait;

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    private function getActiveProject($userId)
    {
        return $this->db->table('renovation_requests')
                        ->where('user_id', $userId)
                        ->orderBy('created_at', 'DESC')
                        ->get()->getRowArray();
    }

    // 1. SUBMIT PENGAJUAN RENOVASI
    public function submit()
    {
        $userId       = $this->request->getPost('user_id');
        $fullName     = $this->request->getPost('full_name');
        $phone        = $this->request->getPost('phone');
        $address      = $this->request->getPost('address');
        $surveyDate   = $this->request->getPost('survey_date');
        $latitude     = $this->request->getPost('latitude');
        $longitude    = $this->request->getPost('longitude');
        
        // FIELD KHUSUS RENOVASI
        $renovType    = $this->request->getPost('renovation_type'); // Total / Kecil
        $description  = $this->request->getPost('description');

        $photoName = null;
        $photo = $this->request->getFile('location_photo');

        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            if (!is_dir('uploads/renovation/')) { mkdir('uploads/renovation/', 0777, true); }
            $photo->move('uploads/renovation', $photoName);
        }

        $data = [
            'user_id'       => $userId,
            'full_name'     => $fullName, 
            'phone'         => $phone,
            'address'       => $address,
            'survey_date'   => $surveyDate,
            'latitude'      => $latitude,
            'longitude'     => $longitude,
            
            'renovation_type' => $renovType,
            'description'     => $description,

            'location_photo'=> $photoName,
            'status'        => 'PENDING',
            'created_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->table('renovation_requests')->insert($data);

        return $this->respondCreated(['status' => true, 'message' => 'Berhasil']);
    }

    // 2. GET LIST PROJECT
    public function project($userId = null)
    {
        $projects = $this->db->table('renovation_requests')
                        ->where('user_id', $userId)
                        ->orderBy('created_at', 'DESC')
                        ->get()->getResultArray();

        return $this->respond(['status' => true, 'data' => $projects]);
    }

    // 3. GET SURVEY (Ambil dari construction_surveys tapi filter id renovasi)
    // Asumsi kamu pakai tabel terpisah 'renovation_surveys'
    public function surveys($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) return $this->respond(['status' => false, 'data' => []]);

        $surveys = $this->db->table('renovation_surveys') // Pastikan tabel ini ada
                            ->where('renovation_id', $project['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();
        
        foreach($surveys as &$item) {
            $item['file_url'] = base_url('uploads/renovation/survey/' . $item['survey_file']);
        }

        return $this->respond(['status' => true, 'data' => $surveys]);
    }

    // 4. GET DESIGNS
    public function designs($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) return $this->respond(['status' => false, 'data' => []]);

        $designs = $this->db->table('renovation_designs') // Pastikan tabel ini ada
                            ->where('renovation_id', $project['id'])
                            ->orderBy('created_at', 'DESC')
                            ->get()->getResultArray();

        foreach($designs as &$item) {
            $item['file_url'] = base_url('uploads/renovation/design/' . $item['file']);
        }

        return $this->respond(['status' => true, 'data' => $designs]);
    }

    // 5. GET PROGRESS
    public function progress($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) return $this->respond(['status' => false, 'data' => []]);

        $progress = $this->db->table('renovation_progress') // Pastikan tabel ini ada
                            ->where('renovation_id', $project['id'])
                            ->orderBy('week', 'DESC')
                            ->get()->getResultArray();

        foreach($progress as &$item) {
            $item['photo_url'] = base_url('uploads/renovation/progress/' . $item['photo']);
        }

        return $this->respond(['status' => true, 'data' => $progress]);
    }

    // 6. GET INVOICES & GENERATE MIDTRANS
    public function invoices($userId = null)
    {
        $project = $this->getActiveProject($userId);
        if (!$project) return $this->respond(['status' => false, 'data' => []]);

        $invoices = $this->db->table('renovation_invoices') // Pastikan tabel ini ada
                             ->where('renovation_id', $project['id'])
                             ->orderBy('created_at', 'ASC')
                             ->get()->getResultArray();

        $data = [];
        foreach ($invoices as $inv) {
            $paymentUrl = $inv['payment_url'];
            
            if ($inv['status'] == 'UNPAID' && empty($paymentUrl)) {
                $paymentUrl = $this->createMidtransTransaction($inv, $project);
                if ($paymentUrl) {
                    $this->db->table('renovation_invoices')->where('id', $inv['id'])->update(['payment_url' => $paymentUrl]);
                }
            }

            $data[] = [
                'id'            => $inv['id'],
                'title'         => $inv['description'] ?? 'Tagihan Renovasi',
                'amount'        => (int)$inv['amount'],
                'due_date'      => $inv['due_date'],
                'status'        => $inv['status'] == 'UNPAID' ? 'UNPAID' : 'PAID',     
                'payment_url'   => $paymentUrl,
                'created_at'    => $inv['created_at']
            ];
        }

        return $this->respond(['status' => true, 'data' => $data]);
    }

    private function createMidtransTransaction($invoice, $project)
    {
        $midtransPath = APPPATH . 'ThirdParty/Midtrans/Midtrans.php';
        if (file_exists($midtransPath)) require_once $midtransPath;

        \Midtrans\Config::$serverKey = 'SB-Mid-server-UKNiwjL6WD2HSFzQ4vP8oKeg'; 
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Prefix RENOV-
        $orderId = 'RENOV-' . $invoice['id'] . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int)$invoice['amount'],
            ],
            'customer_details' => [
                'first_name' => $project['full_name'],
                'phone' => $project['phone'],
            ],
            'item_details' => [
                [
                    'id' => $invoice['id'],
                    'price' => (int)$invoice['amount'],
                    'quantity' => 1,
                    'name' => substr($invoice['description'] ?? 'Tagihan Renovasi', 0, 50)
                ]
            ],
            'callbacks' => [
                'finish' => 'https://pasangin.co.id'
            ]
        ];

        try {
            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $this->db->table('renovation_invoices')->where('id', $invoice['id'])->update([
                'snap_token' => $orderId 
            ]);
            return $paymentUrl;
        } catch (\Exception $e) {
            return null;
        }
    }
}
