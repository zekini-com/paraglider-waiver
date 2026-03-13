<?php

namespace App\Repositories;

use App\DTOs\WaiverSearchFilters;
use App\Models\Waiver;
use App\Repositories\Contracts\WaiverRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class WaiverRepository implements WaiverRepositoryInterface
{
    /** @param array<string, mixed> $data */
    public function create(array $data): Waiver
    {
        return Waiver::create($data);
    }

    public function findById(int $id): Waiver
    {
        return Waiver::findOrFail($id);
    }

    public function findByDownloadToken(string $token): Waiver
    {
        return Waiver::where('download_token', $token)->firstOrFail();
    }

    /** @return LengthAwarePaginator<int, Waiver> */
    public function search(WaiverSearchFilters $filters): LengthAwarePaginator
    {
        $query = Waiver::query()->orderBy('created_at', 'desc');

        if ($filters->query) {
            $search = $filters->query;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id_passport_number', 'like', "%{$search}%");
            });
        }

        if ($filters->dateFrom) {
            $query->whereDate('created_at', '>=', $filters->dateFrom);
        }

        if ($filters->dateTo) {
            $query->whereDate('created_at', '<=', $filters->dateTo);
        }

        return $query->paginate($filters->perPage)->withQueryString();
    }

    /** @param array<string, mixed> $data */
    public function update(Waiver $waiver, array $data): bool
    {
        return $waiver->update($data);
    }
}
