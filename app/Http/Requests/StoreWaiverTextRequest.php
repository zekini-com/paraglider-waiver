<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWaiverTextRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $uniqueRule = 'unique:waiver_texts,version';

        if ($this->route('waiverText')) {
            $uniqueRule .= ','.$this->route('waiverText')->id;
        }

        return [
            'version' => ['required', 'string', 'max:20', $uniqueRule],
            'content' => ['required', 'string', 'min:10'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'version.unique' => 'A waiver text with this version already exists.',
            'content.min' => 'The waiver content must be at least 10 characters.',
        ];
    }
}
