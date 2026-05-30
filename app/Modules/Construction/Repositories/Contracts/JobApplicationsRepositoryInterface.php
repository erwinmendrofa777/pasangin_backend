<?php

namespace App\Modules\Construction\Repositories\Contracts;

interface JobApplicationsRepositoryInterface
{
    public function findByProjectIdAndType(int $projectId, string $projectType): array;
    public function findApprovedByProjectIdAndType(int $projectId, string $projectType): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $data): bool;
}
