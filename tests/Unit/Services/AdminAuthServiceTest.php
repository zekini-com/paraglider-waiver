<?php

namespace Tests\Unit\Services;

use App\Services\AdminAuthService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminAuthServiceTest extends TestCase
{
    private AdminAuthService $service;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'waiver.admin_email' => 'admin@test.com',
            'waiver.admin_password' => 'secret123',
        ]);

        $this->service = new AdminAuthService;
    }

    #[Test]
    public function attempt_returns_true_with_correct_credentials(): void
    {
        $result = $this->service->attempt('admin@test.com', 'secret123');

        $this->assertTrue($result);
    }

    #[Test]
    public function attempt_returns_false_with_wrong_email(): void
    {
        $result = $this->service->attempt('wrong@test.com', 'secret123');

        $this->assertFalse($result);
    }

    #[Test]
    public function attempt_returns_false_with_wrong_password(): void
    {
        $result = $this->service->attempt('admin@test.com', 'wrongpassword');

        $this->assertFalse($result);
    }

    #[Test]
    public function attempt_returns_false_with_both_wrong(): void
    {
        $result = $this->service->attempt('wrong@test.com', 'wrongpassword');

        $this->assertFalse($result);
    }
}
