<?php

namespace App\Repositories\Contracts;

use App\DTOs\WaiverSearchFilters;
use App\Models\Waiver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WaiverRepositoryInterface
{
    /** @param array<string, mixed> $data */
    public function create(array $data): Waiver;

    public function findById(int $id): Waiver;

    public function findByDownloadToken(string $token): Waiver;

    /** @return LengthAwarePaginator<int, Waiver> */
    public function search(WaiverSearchFilters $filters): LengthAwarePaginator;

    /** @param array<string, mixed> $data */
    public function update(Waiver $waiver, array $data): bool;
}
