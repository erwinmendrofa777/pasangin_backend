<?php

namespace App\Modules\SyaratKetentuan\Repositories;

use App\Modules\SyaratKetentuan\Models\TermsOfAgreementModel;
use App\Modules\SyaratKetentuan\Repositories\Contracts\TermsOfAgreementRepositoryInterface;

/**
 * TermsOfAgreementRepository
 */
class TermsOfAgreementRepository implements TermsOfAgreementRepositoryInterface
{
    protected TermsOfAgreementModel $model;

    public function __construct()
    {
        $this->model = new TermsOfAgreementModel();
    }

    public function findByTargetApp(string $target): array
    {
        return $this->model
            ->where('target_app', $target)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function countByTargetApp(string $target): int
    {
        return $this->model->where('target_app', $target)->countAllResults();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id) ?: null;
    }

    public function insert(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->delete($id);
    }
}
