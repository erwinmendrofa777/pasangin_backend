<?php
namespace App\Controllers\Api;
use CodeIgniter\RESTful\ResourceController;

class NotificationApi extends ResourceController {
    protected $format = 'json';

    public function index() {
        $db = \Config\Database::connect();
        $target = $this->request->getVar('target') ?? 'client';
        $data = $db->table('notifications')
                   ->where('target_type', $target)
                   ->orderBy('created_at', 'DESC')
                   ->limit(20)
                   ->get()->getResultArray();
        return $this->respond(['status' => true, 'data' => $data]);
    }
}