<?php

namespace App\Http\Requests;

use App\Enums\EmergencyContactRelationship;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWaiverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->date_of_birth) {
            try {
                $this->merge([
                    'date_of_birth' => Carbon::createFromFormat('d/m/Y', $this->date_of_birth)->format('Y-m-d'),
                ]);
            } catch (\Exception) {
                // Let validation handle invalid format
            }
        }
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'id_passport_number' => 'required|string|max:100',
            'date_of_birth' => 'required|date|before:-18 years',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:50',
            'emergency_contact_relationship' => ['required', Rule::enum(EmergencyContactRelationship::class)],
            'signature_data' => 'required|string',
            'agree_terms' => 'required|accepted',
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'date_of_birth.before' => 'You must be at least 18 years old to sign this waiver.',
            'signature_data.required' => 'Please provide your signature.',
            'agree_terms.accepted' => 'You must agree to the terms and conditions.',
        ];
    }
}
