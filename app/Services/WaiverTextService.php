<?php

namespace App\Services;

use App\Models\WaiverText;
use App\Repositories\Contracts\WaiverTextRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class WaiverTextService
{
    public function __construct(
        private WaiverTextRepositoryInterface $waiverTextRepository,
    ) {}

    /** @return Collection<int, WaiverText> */
    public function all(): Collection
    {
        return $this->waiverTextRepository->all();
    }

    public function findById(int $id): WaiverText
    {
        return $this->waiverTextRepository->findById($id);
    }

    public function getActive(): ?WaiverText
    {
        return $this->waiverTextRepository->getActive();
    }

    public function create(string $version, string $content, bool $setActive = false): WaiverText
    {
        $waiverText = $this->waiverTextRepository->create([
            'version' => $version,
            'content' => $content,
            'is_active' => false,
        ]);

        if ($setActive) {
            $this->waiverTextRepository->setActive($waiverText);
        }

        return $waiverText;
    }

    public function update(WaiverText $waiverText, string $version, string $content): void
    {
        $this->waiverTextRepository->update($waiverText, [
            'version' => $version,
            'content' => $content,
        ]);
    }

    public function setActive(WaiverText $waiverText): void
    {
        $this->waiverTextRepository->setActive($waiverText);
    }
}
