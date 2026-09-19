<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ProductIndexRequest extends PaginationRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'search' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
        ];
    }

    public function search(): ?string
    {
        $search = trim((string) $this->input('search'));

        return $search === '' ? null : $search;
    }

    public function categoryId(): ?int
    {
        return $this->integer('category_id') ?: null;
    }
}
