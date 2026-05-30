<?php

namespace App\Modules\Tukang\Repositories\Contracts;

/**
 * TukangTransactionsRepositoryInterface
 */
interface TukangTransactionsRepositoryInterface
{
    public function insert(array $data): bool;
}
