<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Exception;

use App\Modules\SyaratKetentuan\Models\TermsOfAgreementModel;
use App\Modules\Construction\Models\ConstructionAgreementsModel;
use App\Modules\Renovation\Models\RenovationAgreementsModel;

class AgreementController extends BaseController
{
    use ResponseTrait;
    protected $termsOfAgreementModel;
    protected $constructionAgreementsModel;
    protected $renovationAgreementsModel;
    protected $db;

    public function __construct()
    {
        $this->termsOfAgreementModel = new TermsOfAgreementModel();
        $this->constructionAgreementsModel = new ConstructionAgreementsModel();
        $this->renovationAgreementsModel = new RenovationAgreementsModel();
        $this->db = \Config\Database::connect();
    }

    public function getTermsOfAgreement($targetApp)
    {
        $data = $this->termsOfAgreementModel->where('target_app', $targetApp)->get()->getResultArray();

        if ($data == null) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Data tidak ditemukan.',
                'data'    => []
            ], 200);
        }

        if ($data) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Data berhasil diambil.',
                'data'    => $data
            ], 200);
        }
    }

    public function constructionAgreementsBatch()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        // Validasi struktur data yang dikirim
        if (
            !isset($data['construction_id']) || !isset($data['agreement_id']) || !isset($data['is_checked']) ||
            !is_array($data['agreement_id']) || !is_array($data['is_checked'])
        ) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Format data tidak valid. agreement_id dan is_checked harus array.'
            ], 400);
        }
        $constructionId = $data['construction_id'];
        $agreementIds = $data['agreement_id'];
        $isCheckedList = $data['is_checked'];
        if (count($agreementIds) !== count($isCheckedList)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Jumlah isi data agreement_id dan is_checked tidak cocok.'
            ], 400);
        }
        $this->db->transStart();
        try {
            $insertData = [];
            for ($i = 0; $i < count($agreementIds); $i++) {
                $insertData[] = [
                    'construction_id' => $constructionId,
                    'agreement_id'    => $agreementIds[$i],
                    'is_checked'      => $isCheckedList[$i],
                ];
            }
            if (!empty($insertData)) {
                $this->constructionAgreementsModel->insertBatch($insertData);
            }
            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                return $this->respond([
                    'status'  => 'error',
                    'message' => 'Gagal memproses ke database (transaksi rollback).',
                    'error_detail' => $this->db->error()
                ], 500);
            }
            return $this->respond([
                'status'  => 'success',
                'message' => 'Data berhasil ditambahkan.',
                'data'    => $insertData
            ], 200);
        } catch (Exception $e) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan internal server.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function renovationAgreementsBatch()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        // Validasi struktur data yang dikirim
        if (
            !isset($data['renovation_id']) || !isset($data['agreement_id']) || !isset($data['is_checked']) ||
            !is_array($data['agreement_id']) || !is_array($data['is_checked'])
        ) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Format data tidak valid. agreement_id dan is_checked harus array.'
            ], 400);
        }
        $renovationId = $data['renovation_id'];
        $agreementIds = $data['agreement_id'];
        $isCheckedList = $data['is_checked'];
        if (count($agreementIds) !== count($isCheckedList)) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Jumlah isi data agreement_id dan is_checked tidak cocok.'
            ], 400);
        }
        $this->db->transStart();
        try {
            $insertData = [];
            for ($i = 0; $i < count($agreementIds); $i++) {
                $insertData[] = [
                    'renovation_id' => $renovationId,
                    'agreement_id'    => $agreementIds[$i],
                    'is_checked'      => $isCheckedList[$i],
                ];
            }
            if (!empty($insertData)) {
                $this->renovationAgreementsModel->insertBatch($insertData);
            }
            $this->db->transComplete();
            if ($this->db->transStatus() === false) {
                return $this->respond([
                    'status'  => 'error',
                    'message' => 'Gagal memproses ke database (transaksi rollback).',
                    'error_detail' => $this->db->error()
                ], 500);
            }
            return $this->respond([
                'status'  => 'success',
                'message' => 'Data berhasil ditambahkan.',
                'data'    => $insertData
            ], 200);
        } catch (Exception $e) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan internal server.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
