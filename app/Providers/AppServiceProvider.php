<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)->by(
            Str::lower((string) $request->input('email')).'|'.$request->ip(),
        ));

        // Auto-purge Cloudflare cache saat konten toko berubah.
        // ponytail: purge_everything via HTTP sinkron, env() dipakai selama config belum di-cache;
        // upgrade path: queue + purge per-tag (header Cache-Tag di response) bila edit makin sering.
        $purge = function (): void {
            $zone = env('CLOUDFLARE_ZONE_ID');
            $token = env('CLOUDFLARE_PURGE_TOKEN');
            if (! $zone || ! $token) {
                return; // tanpa credential (CI/test): lewati
            }
            $ctx = stream_context_create(['http' => [
                'method' => 'POST',
                'header' => "Authorization: Bearer {$token}\r\nContent-Type: application/json\r\n",
                'content' => '{"purge_everything":true}',
                'timeout' => 3,
                'ignore_errors' => true,
            ]]);
            @file_get_contents("https://api.cloudflare.com/client/v4/zones/{$zone}/purge_cache", false, $ctx);
        };
        foreach ([Product::class, Category::class, StoreSetting::class] as $eloquentModel) {
            $eloquentModel::saved($purge);
            $eloquentModel::deleted($purge);
        }
    }
}
