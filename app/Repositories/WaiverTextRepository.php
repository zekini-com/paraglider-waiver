<?php

namespace App\Repositories;

use App\Models\WaiverText;
use App\Repositories\Contracts\WaiverTextRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WaiverTextRepository implements WaiverTextRepositoryInterface
{
    /** @return Collection<int, WaiverText> */
    public function all(): Collection
    {
        return WaiverText::orderBy('created_at', 'desc')->get();
    }

    public function findById(int $id): WaiverText
    {
        return WaiverText::findOrFail($id);
    }

    public function getActive(): ?WaiverText
    {
        return WaiverText::where('is_active', true)->first();
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): WaiverText
    {
        return WaiverText::create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(WaiverText $waiverText, array $data): bool
    {
        return $waiverText->update($data);
    }

    public function setActive(WaiverText $waiverText): void
    {
        WaiverText::where('is_active', true)->update(['is_active' => false]);
        $waiverText->update(['is_active' => true]);
    }
}
