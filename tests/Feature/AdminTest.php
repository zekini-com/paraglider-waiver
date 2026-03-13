<?php

namespace Tests\Feature;

use App\Models\Waiver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'waiver.admin_email' => 'admin@test.com',
            'waiver.admin_password' => 'secret123',
        ]);
    }

    private function authenticateAdmin(): static
    {
        return $this->withSession(['admin_authenticated' => true]);
    }

    #[Test]
    public function login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function successful_login_redirects_to_dashboard(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    #[Test]
    public function successful_login_sets_session(): void
    {
        $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        $this->assertSame(true, session('admin_authenticated'));
    }

    #[Test]
    public function failed_login_shows_error(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect();
    }

    #[Test]
    public function failed_login_with_invalid_email_format(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'not-an-email',
            'password' => 'secret123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function login_requires_email_and_password(): void
    {
        $response = $this->post('/admin/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    #[Test]
    public function dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function dashboard_loads_when_authenticated(): void
    {
        $response = $this->authenticateAdmin()->get('/admin/dashboard');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function dashboard_shows_waivers(): void
    {
        $waiver = Waiver::factory()->create([
            'first_name' => 'Alice',
            'last_name' => 'Wonderland',
            'email' => 'alice@example.com',
        ]);

        $response = $this->authenticateAdmin()->get('/admin/dashboard');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Alice');
        $response->assertSee('Wonderland');
    }

    #[Test]
    public function dashboard_search_filters_by_query(): void
    {
        Waiver::factory()->create([
            'first_name' => 'Alice',
            'last_name' => 'Wonderland',
        ]);
        Waiver::factory()->create([
            'first_name' => 'Bob',
            'last_name' => 'Builder',
        ]);

        $response = $this->authenticateAdmin()->get('/admin/dashboard?q=Alice');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Alice');
        $response->assertDontSee('Bob');
    }

    #[Test]
    public function dashboard_search_filters_by_date_range(): void
    {
        Waiver::factory()->create([
            'first_name' => 'OldWaiver',
            'created_at' => '2025-01-01 12:00:00',
        ]);
        Waiver::factory()->create([
            'first_name' => 'NewWaiver',
            'created_at' => '2026-03-10 12:00:00',
        ]);

        $response = $this->authenticateAdmin()
            ->get('/admin/dashboard?date_from=01/03/2026&date_to=15/03/2026');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('NewWaiver');
        $response->assertDontSee('OldWaiver');
    }

    #[Test]
    public function waiver_detail_page_requires_authentication(): void
    {
        $waiver = Waiver::factory()->create();

        $response = $this->get("/admin/waiver/{$waiver->id}");

        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function waiver_detail_page_loads_when_authenticated(): void
    {
        $waiver = Waiver::factory()->create([
            'first_name' => 'Charlie',
            'last_name' => 'Brown',
            'email' => 'charlie@example.com',
        ]);

        $response = $this->authenticateAdmin()->get("/admin/waiver/{$waiver->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Charlie');
        $response->assertSee('Brown');
    }

    #[Test]
    public function waiver_detail_returns_404_for_nonexistent_waiver(): void
    {
        $response = $this->authenticateAdmin()->get('/admin/waiver/99999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function logout_clears_session_and_redirects_to_login(): void
    {
        $response = $this->authenticateAdmin()->post('/admin/logout');

        $response->assertRedirect(route('admin.login'));
    }

    #[Test]
    public function after_logout_dashboard_is_inaccessible(): void
    {
        // Login first
        $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'secret123',
        ]);

        // Logout
        $this->post('/admin/logout');

        // Try to access dashboard
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
    }
}
