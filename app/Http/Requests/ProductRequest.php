<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('products', 'name')->ignore($this->route('product')),
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'price' => ['required', 'integer', 'min:0', 'max:999999999'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
        ]);
    }
}
