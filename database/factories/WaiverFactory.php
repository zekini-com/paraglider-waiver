<?php

namespace Database\Factories;

use App\Enums\EmergencyContactRelationship;
use App\Models\Waiver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Waiver> */
class WaiverFactory extends Factory
{
    protected $model = Waiver::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'id_passport_number' => fake()->numerify('##########'),
            'date_of_birth' => fake()->date('Y-m-d', '-18 years'),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->phoneNumber(),
            'emergency_contact_relationship' => fake()->randomElement(EmergencyContactRelationship::cases()),
            'waiver_version' => '1.0',
            'signature_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==',
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'download_token' => Str::random(64),
        ];
    }
}
