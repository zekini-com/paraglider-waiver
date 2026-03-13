<?php

namespace Tests\Feature;

use App\Enums\EmergencyContactRelationship;
use App\Models\Waiver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WaiverSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private const SIGNATURE_DATA = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    private function validWaiverData(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+27821234567',
            'id_passport_number' => '9001015009087',
            'date_of_birth' => '01/01/1990',
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+27829876543',
            'emergency_contact_relationship' => 'Spouse',
            'signature_data' => self::SIGNATURE_DATA,
            'agree_terms' => '1',
        ], $overrides);
    }

    #[Test]
    public function landing_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function waiver_form_loads_successfully(): void
    {
        $response = $this->get('/waiver');

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function waiver_form_shows_relationship_options(): void
    {
        $response = $this->get('/waiver');

        $response->assertStatus(Response::HTTP_OK);

        foreach (EmergencyContactRelationship::cases() as $relationship) {
            $response->assertSee($relationship->value);
        }
    }

    #[Test]
    public function successful_waiver_submission_redirects_to_confirmation(): void
    {
        Mail::fake();

        $response = $this->post('/waiver', $this->validWaiverData());

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirectContains('/waiver/confirm');

        $this->assertDatabaseHas('waivers', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+27821234567',
            'id_passport_number' => '9001015009087',
            'date_of_birth' => '1990-01-01',
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_phone' => '+27829876543',
            'emergency_contact_relationship' => 'Spouse',
        ]);
    }

    #[Test]
    public function waiver_submission_stores_waiver_version(): void
    {
        Mail::fake();

        $this->post('/waiver', $this->validWaiverData());

        $this->assertDatabaseHas('waivers', [
            'email' => 'john@example.com',
            'waiver_version' => '1.0',
        ]);
    }

    #[Test]
    public function waiver_submission_generates_download_token(): void
    {
        Mail::fake();

        $this->post('/waiver', $this->validWaiverData());

        $waiver = Waiver::where('email', 'john@example.com')->first();

        $this->assertNotNull($waiver);
        $this->assertNotEmpty($waiver->download_token);
        $this->assertSame(64, strlen($waiver->download_token));
    }

    #[Test]
    public function validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->post('/waiver', []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'phone',
            'id_passport_number',
            'date_of_birth',
            'emergency_contact_name',
            'emergency_contact_phone',
            'emergency_contact_relationship',
            'signature_data',
            'agree_terms',
        ]);
    }

    #[Test]
    public function validation_fails_for_underage_participant(): void
    {
        $response = $this->post('/waiver', $this->validWaiverData([
            'date_of_birth' => '01/01/2020',
        ]));

        $response->assertSessionHasErrors(['date_of_birth']);
    }

    #[Test]
    public function validation_fails_for_invalid_email(): void
    {
        $response = $this->post('/waiver', $this->validWaiverData([
            'email' => 'not-an-email',
        ]));

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function validation_fails_for_invalid_relationship(): void
    {
        $response = $this->post('/waiver', $this->validWaiverData([
            'emergency_contact_relationship' => 'InvalidRelationship',
        ]));

        $response->assertSessionHasErrors(['emergency_contact_relationship']);
    }

    #[Test]
    public function validation_fails_when_terms_not_accepted(): void
    {
        $response = $this->post('/waiver', $this->validWaiverData([
            'agree_terms' => '0',
        ]));

        $response->assertSessionHasErrors(['agree_terms']);
    }

    #[Test]
    public function confirmation_page_loads_with_valid_token(): void
    {
        $waiver = Waiver::factory()->create();

        $response = $this->get('/waiver/confirm?token='.$waiver->download_token);

        $response->assertStatus(Response::HTTP_OK);
    }

    #[Test]
    public function confirmation_page_returns_404_with_invalid_token(): void
    {
        $response = $this->get('/waiver/confirm?token=invalid-token-that-does-not-exist');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function confirmation_page_returns_404_without_token(): void
    {
        $response = $this->get('/waiver/confirm');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
