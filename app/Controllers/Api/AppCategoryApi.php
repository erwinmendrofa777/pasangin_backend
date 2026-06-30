<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Products\Models\AppCategoryModel;

class AppCategoryApi extends ResourceController
{
    public function index()
    {
        $model = new AppCategoryModel();
        $categories = $model->orderBy('name', 'ASC')->findAll();

        return $this->response->setJSON($categories);
    }
}
