<?php

namespace App\Repositories\Contracts;

use App\Models\WaiverText;
use Illuminate\Database\Eloquent\Collection;

interface WaiverTextRepositoryInterface
{
    /** @return Collection<int, WaiverText> */
    public function all(): Collection;

    public function findById(int $id): WaiverText;

    public function getActive(): ?WaiverText;

    /** @param array<string, mixed> $data */
    public function create(array $data): WaiverText;

    /** @param array<string, mixed> $data */
    public function update(WaiverText $waiverText, array $data): bool;

    public function setActive(WaiverText $waiverText): void;
}
