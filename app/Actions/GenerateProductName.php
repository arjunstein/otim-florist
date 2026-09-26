<?php

namespace App\Actions;

use App\Models\Category;
use App\Models\Product;

class GenerateProductName
{
    public function handle(string $rawName, Category $category): string
    {
        $baseName = trim((string) preg_replace('/\s+[A-Za-z]+-\d+$/', '', $rawName));
        $code = $this->categoryCode($category);
        $number = $this->nextProductNumber($category, $code);

        do {
            $name = sprintf('%s %s-%02d', $baseName, $code, $number);
            $number++;
        } while (Product::query()->where('name', $name)->exists());

        return $name;
    }

    public function __invoke(string $rawName, Category $category): string
    {
        return $this->handle($rawName, $category);
    }

    private function categoryCode(Category $category): string
    {
        $words = preg_split('/\s+/', trim($category->name)) ?: [];
        $letters = array_map(fn (string $word): string => mb_substr($word, 0, 1), $words);

        return mb_strtoupper(implode('', $letters));
    }

    private function nextProductNumber(Category $category, string $code): int
    {
        $lastNumber = Product::query()
            ->where('category_id', $category->id)
            ->where('name', 'like', "% {$code}-%")
            ->pluck('name')
            ->map(function (string $name) use ($code): int {
                if (preg_match('/ '.preg_quote($code, '/').'-(\d+)$/', $name, $matches)) {
                    return (int) $matches[1];
                }

                return 0;
            })
            ->max() ?? 0;

        return $lastNumber + 1;
    }
}
