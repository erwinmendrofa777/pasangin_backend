<?php

namespace App\Controllers\Api;

use App\Modules\Supplier\Models\SupplierModel;
use App\Modules\Supplier\Models\SupplierReferralModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;

class SalesAssistanceController extends ResourceController
{
    protected $format = 'json';

    /**
     * POST /api/supplier/referral/generate
     * Dijalankan oleh Supplier untuk menghasilkan kode scan dinamis
     */
    public function generateReferralCode()
    {
        // Pastikan login sebagai supplier
        if (!isset($this->request->user) || $this->request->user->role !== 'supplier') {
            return $this->failUnauthorized('Akses ditolak. Hanya supplier yang dapat menghasilkan kode referal.');
        }

        $supplierId = $this->request->user->uid;
        $referralModel = new SupplierReferralModel();

        try {
            // Generate 8-character unique uppercase string
            $code = 'SUP-' . strtoupper(bin2hex(random_bytes(3)));
            
            // Set kedaluwarsa 10 menit
            $expiresAt = date('Y-m-d H:i:s', time() + 600);

            $referralModel->insert([
                'supplier_id' => $supplierId,
                'code'        => $code,
                'expires_at'  => $expiresAt,
                'is_used'     => 0
            ]);

            return $this->respond([
                'status'  => true,
                'message' => 'Kode referal berhasil dibuat.',
                'data'    => [
                    'code'       => $code,
                    'expires_at' => $expiresAt
                ]
            ]);
        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }

    /**
     * POST /api/sales/claim-supplier
     * Dijalankan oleh Sales (via Web Dashboard / API) untuk menghubungkan akun supplier
     */
    public function claimSupplier()
    {
        // Mengambil sales_id dari session dashboard (web) atau token JWT
        $salesId = null;
        if (session()->get('isLoggedIn') && session()->get('role') === 'sales') {
            $salesId = session()->get('user_id');
        } elseif (isset($this->request->user) && $this->request->user->role === 'sales') {
            $salesId = $this->request->user->uid;
        }

        if (!$salesId) {
            return $this->failUnauthorized('Akses ditolak. Hanya Sales yang terautentikasi yang dapat mengklaim supplier.');
        }

        $code = $this->request->getVar('code');
        if (empty($code)) {
            return $this->fail('Kode referal wajib diisi.', 400);
        }

        $referralModel = new SupplierReferralModel();
        $supplierModel = new SupplierModel();

        try {
            // Cari data kode referal
            $refData = $referralModel->where('code', $code)->first();

            if (!$refData) {
                return $this->failNotFound('Kode referal tidak ditemukan.');
            }

            if ($refData['is_used'] == 1) {
                return $this->fail('Kode referal sudah digunakan.', 400);
            }

            if (strtotime($refData['expires_at']) < time()) {
                return $this->fail('Kode referal telah kedaluwarsa.', 400);
            }

            // Mulai transaksi DB
            $db = \Config\Database::connect();
            $db->transStart();

            // 1. Hubungkan supplier ke sales
            $supplierModel->update($refData['supplier_id'], [
                'sales_id' => $salesId
            ]);

            // 2. Tandai kode referal sebagai terpakai
            $referralModel->update($refData['id'], [
                'is_used' => 1
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->failServerError('Gagal mengklaim supplier. Terjadi kesalahan sistem.');
            }

            $supplierInfo = $supplierModel->find($refData['supplier_id']);

            return $this->respond([
                'status'  => true,
                'message' => 'Supplier berhasil dihubungkan.',
                'data'    => [
                    'supplier_id'   => $supplierInfo['id'],
                    'supplier_name' => $supplierInfo['name']
                ]
            ]);

        } catch (Exception $e) {
            return $this->failServerError($e->getMessage());
        }
    }
}
