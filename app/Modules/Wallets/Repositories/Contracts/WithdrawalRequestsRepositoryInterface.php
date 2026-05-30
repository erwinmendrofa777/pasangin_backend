<?php

namespace App\Modules\Wallets\Repositories\Contracts;

interface WithdrawalRequestsRepositoryInterface
{
    public function findAllWithTukang(): array;
    public function findById(int $id): ?array;
    public function update(int $id, array $data): bool;
    public function getLatestRequests(int $limit): array;
}
