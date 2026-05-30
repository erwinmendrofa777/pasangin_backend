<?php

namespace App\Modules\SyaratKetentuan\Repositories\Contracts;

/**
 * TermsOfAgreementRepositoryInterface
 */
interface TermsOfAgreementRepositoryInterface
{
    public function findByTargetApp(string $target): array;
    public function countByTargetApp(string $target): int;
    public function findById(int $id): ?array;
    public function insert(array $data): bool;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
