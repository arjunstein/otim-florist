<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('storefront.home') }}</loc>
    </url>
    @foreach ($categories as $category)
        <url>
            <loc>{{ route('storefront.categories.show', $category) }}</loc>
            <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
    @foreach ($products as $product)
        <url>
            <loc>{{ route('storefront.products.show', $product) }}</loc>
            <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>
