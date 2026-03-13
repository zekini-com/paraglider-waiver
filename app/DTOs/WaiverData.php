<?php

namespace App\DTOs;

use App\Http\Requests\StoreWaiverRequest;

readonly class WaiverData
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $phone,
        public string $idPassportNumber,
        public string $dateOfBirth,
        public string $emergencyContactName,
        public string $emergencyContactPhone,
        public string $emergencyContactRelationship,
        public string $signatureData,
        public string $ipAddress,
        public string $userAgent,
    ) {}

    public static function fromRequest(StoreWaiverRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            firstName: $validated['first_name'],
            lastName: $validated['last_name'],
            email: $validated['email'],
            phone: $validated['phone'],
            idPassportNumber: $validated['id_passport_number'],
            dateOfBirth: $validated['date_of_birth'],
            emergencyContactName: $validated['emergency_contact_name'],
            emergencyContactPhone: $validated['emergency_contact_phone'],
            emergencyContactRelationship: $validated['emergency_contact_relationship'],
            signatureData: $validated['signature_data'],
            ipAddress: $request->ip() ?? '',
            userAgent: $request->userAgent() ?? '',
        );
    }

    /** @return array<string, string> */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'id_passport_number' => $this->idPassportNumber,
            'date_of_birth' => $this->dateOfBirth,
            'emergency_contact_name' => $this->emergencyContactName,
            'emergency_contact_phone' => $this->emergencyContactPhone,
            'emergency_contact_relationship' => $this->emergencyContactRelationship,
            'signature_data' => $this->signatureData,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
        ];
    }
}
