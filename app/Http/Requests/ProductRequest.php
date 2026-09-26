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
                'max:110',
                Rule::when(
                    $this->route('product') !== null,
                    [Rule::unique('products', 'name')->ignore($this->route('product'))],
                ),
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'price' => ['required', 'integer', 'min:0', 'max:999999999'],
            'sale_price' => ['nullable', 'integer', 'min:0', 'lt:price'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:3072'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'product name',
            'category_id' => 'category',
            'price' => 'price',
            'sale_price' => 'sale price',
            'description' => 'description',
            'image' => 'product image',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'name.max' => 'Product name may not exceed :max characters.',
            'name.unique' => 'A product with this name already exists.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category does not exist.',
            'price.required' => 'Product price is required.',
            'price.integer' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'sale_price.integer' => 'Sale price must be a valid number.',
            'sale_price.min' => 'Sale price cannot be negative.',
            'sale_price.lt' => 'Sale price must be lower than the regular price.',
            'description.max' => 'Description may not exceed :max characters.',
            'image.image' => 'The uploaded file must be an image (JPG, PNG, or WebP).',
            'image.max' => 'Product image size must not exceed 3 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'description' => filled($this->input('description')) ? trim((string) $this->input('description')) : null,
            'sale_price' => filled($this->input('sale_price')) ? $this->input('sale_price') : null,
        ]);
    }
}
