<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Modules\Notifications\Repositories\NotificationRepository;
use App\Modules\Notifications\Repositories\Contracts\NotificationRepositoryInterface;

/**
 * NotificationApi
 *
 * Endpoint publik untuk mengambil riwayat notifikasi berdasarkan target_type.
 * Diakses dari: GET api/notifications?target=client
 *
 * Route: api/notifications (GET) — lihat Routes.php baris 481
 */
class NotificationApi extends ResourceController
{
    protected $format = 'json';
    protected NotificationRepositoryInterface $notificationRepository;

    public function __construct()
    {
        $this->notificationRepository = new NotificationRepository();
    }

    /**
     * GET api/notifications?target={client|tukang|supplier}&limit={n}
     */
    public function index()
    {
        $target = $this->request->getVar('target') ?? 'client';
        $limit  = (int) ($this->request->getVar('limit') ?? 20);

        // Validasi nilai target yang diizinkan
        $allowedTargets = ['client', 'tukang', 'supplier'];
        if (!in_array($target, $allowedTargets, true)) {
            return $this->fail('Parameter target tidak valid. Gunakan: client, tukang, atau supplier.', 400);
        }

        $data = $this->notificationRepository->findByTargetType($target, $limit);

        return $this->respond([
            'status' => true,
            'target' => $target,
            'count'  => count($data),
            'data'   => $data,
        ]);
    }
}