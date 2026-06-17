<?php

namespace App\Modules\AboutApplication\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

use App\Modules\AboutApplication\Models\AboutApplicationPasanginModel;

class AboutApplicationPasanginControllerApi extends BaseController
{
    use ResponseTrait;
    protected $aboutApplicationPasanginModel;

    public function __construct()
    {
        $this->aboutApplicationPasanginModel = new AboutApplicationPasanginModel();
    }

    public function getAboutApplicationPasangin()
    {
        $data = $this->aboutApplicationPasanginModel->findAll();
        if ($data == null) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Data tidak ditemukan atau kosong.',
                'data' => []
            ], 200);
        }
        if ($data) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Data berhasil diambil.',
                'data' => $data
            ], 200);
        }
    }
}
