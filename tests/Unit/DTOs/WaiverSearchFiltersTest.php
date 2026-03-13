<?php

namespace Tests\Unit\DTOs;

use App\DTOs\WaiverSearchFilters;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WaiverSearchFiltersTest extends TestCase
{
    #[Test]
    public function it_has_sensible_defaults(): void
    {
        $filters = new WaiverSearchFilters;

        $this->assertNull($filters->query);
        $this->assertNull($filters->dateFrom);
        $this->assertNull($filters->dateTo);
        $this->assertSame(20, $filters->perPage);
    }

    #[Test]
    public function from_request_extracts_query_parameter(): void
    {
        $request = Request::create('/admin/dashboard', 'GET', ['q' => 'John']);

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertSame('John', $filters->query);
    }

    #[Test]
    public function from_request_parses_dates_from_dmy_to_ymd(): void
    {
        $request = Request::create('/admin/dashboard', 'GET', [
            'date_from' => '15/03/2026',
            'date_to' => '20/03/2026',
        ]);

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertSame('2026-03-15', $filters->dateFrom);
        $this->assertSame('2026-03-20', $filters->dateTo);
    }

    #[Test]
    public function from_request_returns_null_dates_when_not_provided(): void
    {
        $request = Request::create('/admin/dashboard', 'GET');

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertNull($filters->dateFrom);
        $this->assertNull($filters->dateTo);
    }

    #[Test]
    public function from_request_uses_default_per_page_of_20(): void
    {
        $request = Request::create('/admin/dashboard', 'GET');

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertSame(20, $filters->perPage);
    }

    #[Test]
    public function from_request_respects_custom_per_page(): void
    {
        $request = Request::create('/admin/dashboard', 'GET', ['per_page' => '50']);

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertSame(50, $filters->perPage);
    }

    #[Test]
    public function from_request_caps_per_page_at_100(): void
    {
        $request = Request::create('/admin/dashboard', 'GET', ['per_page' => '500']);

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertSame(100, $filters->perPage);
    }

    #[Test]
    public function from_request_passes_through_invalid_date_format(): void
    {
        $request = Request::create('/admin/dashboard', 'GET', [
            'date_from' => 'not-a-date',
        ]);

        $filters = WaiverSearchFilters::fromRequest($request);

        $this->assertSame('not-a-date', $filters->dateFrom);
    }
}
