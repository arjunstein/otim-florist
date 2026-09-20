<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^628[0-9]{7,12}$/'],
            'address' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = preg_replace('/\D+/', '', (string) $this->input('phone'));

        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $this->merge([
            'name' => trim((string) $this->input('name')),
            'phone' => $phone,
            'address' => trim((string) $this->input('address')),
            'hours' => trim((string) $this->input('hours')),
        ]);
    }
}
