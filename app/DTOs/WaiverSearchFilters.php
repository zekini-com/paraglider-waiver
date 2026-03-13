<?php

namespace App\DTOs;

use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class WaiverSearchFilters
{
    public function __construct(
        public ?string $query = null,
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public int $perPage = 20,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            query: $request->query('q'),
            dateFrom: self::parseDate($request->query('date_from')),
            dateTo: self::parseDate($request->query('date_to')),
            perPage: min((int) $request->query('per_page', 20), 100),
        );
    }

    private static function parseDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception) {
            return $date;
        }
    }
}
