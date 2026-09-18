<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard/Categories', [
            'categories' => Category::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'createdAt' => $category->created_at->format('d M Y'),
                ]),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return to_route('categories.index')->with('success', 'Category created.');
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return to_route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return to_route('categories.index')->with('error', 'Category cannot be deleted while it has products.');
        }

        $category->delete();

        return to_route('categories.index')->with('success', 'Category deleted.');
    }
}
