<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StorefrontController extends Controller
{
    public function index(Request $request): Response
    {
        $latestProductWithImage = Product::query()
            ->whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->latest('id')
            ->first();

        $ogImage = $latestProductWithImage?->image_path
            ? url(Storage::disk('public')->url($latestProductWithImage->image_path))
            : asset('images/og-image.jpg');

        $category = $request->query('category');
        $category = is_string($category) ? $category : null;
        $products = Product::query()
            ->select(['id', 'category_id', 'name', 'slug', 'description', 'image_path', 'price', 'sale_price'])
            ->with('category:id,name,slug')
            ->when($category, fn ($query) => $query->whereHas('category', fn ($query) => $query->where('slug', $category)))
            ->orderByDesc('click_count')->orderByDesc('id')
            ->paginate(12)->withQueryString();

        return Inertia::render('Storefront/Catalog', [
            'canonicalUrl' => route('storefront.home'),
            'ogImage' => $ogImage,
            'navigationCategories' => $this->navigationCategories(),
            'products' => $products->getCollection()->map(fn (Product $product) => $this->productData($product)),
            'pagination' => $this->paginationData($products),
            'selectedCategory' => $category,
            'availableCategories' => Category::query()->whereHas('products')->orderBy('name')->get(['name', 'slug']),
        ]);
    }

    public function showCategory(Category $category): Response
    {
        $category->loadCount('products');
        $products = $category->products()
            ->select(['products.id', 'products.category_id', 'products.name', 'products.slug', 'products.description', 'products.image_path', 'products.price', 'products.sale_price'])
            ->with('category:id,name,slug')
            ->orderBy('name')
            ->paginate(12)->withQueryString();

        $latestProductWithImage = $category->products()
            ->whereNotNull('image_path')
            ->where('image_path', '!=', '')
            ->latest('id')
            ->first();

        $ogImage = $latestProductWithImage?->image_path
            ? url(Storage::disk('public')->url($latestProductWithImage->image_path))
            : asset('images/og-image.jpg');

        $storeName = StoreSetting::current()->name ?: 'Otim Florist';

        return Inertia::render('Storefront/Category', [
            'canonicalUrl' => route('storefront.categories.show', $category),
            'ogImage' => $ogImage,
            'ogTitle' => "Jual {$category->name} Jakarta | {$storeName}",
            'ogDescription' => "Beli {$category->name} Jakarta murah berkualitas dari {$storeName}. Gratis ongkir Jakbar & Jakpus. Pesan sekarang via WhatsApp.",
            'navigationCategories' => $this->navigationCategories(),
            'category' => $this->categoryData($category),
            'products' => $products->getCollection()->map(fn (Product $product) => $this->productData($product)),
            'pagination' => $this->paginationData($products),
        ]);
    }

    public function showProduct(Product $product): Response
    {
        $product->load('category:id,name,slug');

        $ogImage = $product->image_path
            ? url(Storage::disk('public')->url($product->image_path))
            : asset('images/og-image.jpg');

        $storeName = StoreSetting::current()->name ?: 'Otim Florist';
        $price = number_format($product->sale_price ?? $product->price, 0, ',', '.');
        $productDescription = "{$product->name} mulai Rp{$price} — kirim Jakarta. Pesan cepat via WA.";

        return Inertia::render('Storefront/Product', [
            'canonicalUrl' => route('storefront.products.show', $product),
            'ogImage' => $ogImage,
            'ogTitle' => "{$product->name} | {$storeName} Jakarta",
            'ogDescription' => $productDescription,
            'navigationCategories' => $this->navigationCategories(),
            'product' => $this->productData($product),
            'whatsappUrl' => $this->whatsappUrl($product),
        ]);
    }

    public function recordClick(Request $request, Product $product): HttpResponse
    {
        $key = 'product_click_'.$product->id;
        if ($request->session()->get($key, 0) <= now()->subMinutes(30)->timestamp) {
            Product::query()->whereKey($product->getKey())->toBase()->increment('click_count');
            $request->session()->put($key, now()->timestamp);
        }

        return response()->noContent();
    }

    private function paginationData(LengthAwarePaginator $products): array
    {
        return [
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage(),
            'total' => $products->total(),
            'from' => $products->firstItem(),
            'to' => $products->lastItem(),
            'previousUrl' => $products->previousPageUrl(),
            'nextUrl' => $products->nextPageUrl(),
        ];
    }

    public function about(): Response
    {
        return Inertia::render('Storefront/About', [
            'canonicalUrl' => route('storefront.about'),
            'navigationCategories' => $this->navigationCategories(),
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Storefront/Contact', [
            'canonicalUrl' => route('storefront.contact'),
            'navigationCategories' => $this->navigationCategories(),
        ]);
    }

    /**
     * @return array<int, array{name: string, slug: string}>
     */
    private function navigationCategories(): array
    {
        return Category::navigation();
    }

    /**
     * @return array{id: int, name: string, slug: string, productCount: int}
     */
    private function categoryData(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'productCount' => $category->products_count ?? 0,
        ];
    }

    /**
     * @return array{id: int, name: string, slug: string, description: ?string, imageUrl: ?string, price: int, salePrice: ?int, category: array{id: int, name: string, slug: string}}
     */
    private function productData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'imageUrl' => $product->image_path ? Storage::disk('public')->url($product->image_path) : null,
            'price' => $product->price,
            'salePrice' => $product->sale_price,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ],
        ];
    }

    private function whatsappUrl(Product $product): ?string
    {
        $store = StoreSetting::current();

        if (! $store->phone) {
            return null;
        }

        $storeName = $store->name ?: 'Otim Florist';
        $price = number_format($product->sale_price ?? $product->price, 0, ',', '.');
        $productUrl = route('storefront.products.show', $product);
        $message = "Halo {$storeName}, saya ingin memesan {$product->name} (Rp {$price}).\n\nLink produk: {$productUrl}";

        return 'https://wa.me/'.$store->phone.'?text='.rawurlencode($message);
    }
}
