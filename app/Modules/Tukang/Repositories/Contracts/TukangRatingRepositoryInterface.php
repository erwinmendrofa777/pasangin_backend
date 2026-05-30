<?php

namespace App\Modules\Tukang\Repositories\Contracts;

/**
 * TukangRatingRepositoryInterface
 */
interface TukangRatingRepositoryInterface
{
    public function findByTukangId(int $tukangId): array;
}
