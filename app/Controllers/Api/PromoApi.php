<?php

namespace App\Controllers\Api;

use App\Models\PromoModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class PromoApi extends ResourceController
{
    protected $format = 'json';
    private $jwtKey = 'ijskksjncc8sjskalxmmdkdlelmxnk344msm,smmfnfk00mma';

    /**
     * HELPER: Mendapatkan ID Supplier dari Token JWT
     */
    private function getSupplierId()
    {
        try {
            $authHeader = $this->request->getHeaderLine('Authorization');
            $token = str_replace('Bearer ', '', $authHeader);
            $tokenParts = explode('.', $token);
            if (count($tokenParts) != 3) return null;
            $payload = json_decode(base64_decode($tokenParts[1]), true);
            return $payload['uid'] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * --- 1. LIST PROMO SAYA ---
     */
    public function index()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        $model = new PromoModel();
        $data = $model->where('supplier_id', $supplierId)
                      ->orderBy('id', 'DESC')
                      ->findAll();

        return $this->respond($data);
    }

    public function show($supplier_id = null)
    {
        $model = new PromoModel();
        $data = $model->where('supplier_id', $supplier_id)->findAll();
        
        if (!$data) return $this->failNotFound();

        return $this->respond($data);
    }

    public function getAllPromo()
    {
        $model = new PromoModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    /**
     * --- 2. TAMBAH PROMO ---
     */
    public function create()
    {
        $supplierId = $this->getSupplierId();
        if (!$supplierId) return $this->failUnauthorized();

        $data = $this->request->getPost();
        
        // Logika Upload Foto Promo
        $file = $this->request->getFile('photo');
        $photoName = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $photoName = $file->getRandomName();
            $file->move('uploads/promos/', $photoName);
        }

        $model = new PromoModel();
        try {
            $model->insert([
                'supplier_id'    => $supplierId,
                'title'          => $data['title'],
                'description'    => $data['description'] ?? null,
                'discount_type'  => $data['discount_type'] ?? 'percentage',
                'discount_value' => $data['discount_value'],
                'promo_code'     => $data['promo_code'] ?? null,
                'start_date'     => $data['start_date'] ?? null,
                'end_date'       => $data['end_date'] ?? null,
                'status'         => 'active',
                'photo'          => $photoName
            ]);

            return $this->respondCreated(['status' => true, 'message' => 'Promo berhasil dibuat.']);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * --- 3. HAPUS PROMO ---
     */
    public function delete($id = null)
    {
        $supplierId = $this->getSupplierId();
        $model = new PromoModel();
        $promo = $model->where(['id' => $id, 'supplier_id' => $supplierId])->first();
        
        if (!$promo) return $this->failNotFound();

        if (!empty($promo['photo']) && file_exists('uploads/promos/' . $promo['photo'])) {
            unlink('uploads/promos/' . $promo['photo']);
        }

        $model->delete($id);
        return $this->respondDeleted(['status' => true, 'message' => 'Promo dihapus.']);
    }
}