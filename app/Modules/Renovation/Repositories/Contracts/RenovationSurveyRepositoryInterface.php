<?php

namespace App\Modules\Renovation\Repositories\Contracts;

interface RenovationSurveyRepositoryInterface
{
    public function findByRequestId(int $id): array;
    public function insert(array $data): bool;
    public function delete(int $id): bool;
}
