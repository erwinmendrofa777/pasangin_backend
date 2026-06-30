<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Satuan\Models\SatuanModel;

class SatuanApi extends ResourceController
{
    public function index()
    {
        $satuanModel = new SatuanModel();
        $satuan = $satuanModel->findAll();

        return $this->response->setJSON($satuan);
    }
}