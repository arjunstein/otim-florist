<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class Visitor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function saveVisitor()
    {
        $ip = Request::ip();
        $cacheKey = 'last_visited_' . $ip;

        // Cek jika IP sudah tersimpan dalam cache untuk periode 1 jam
        if (Cache::has($cacheKey)) {
            return;
        }

        // Cek apakah IP ini sudah ada di database dalam 1 jam terakhir
        $oneHourAgo = Carbon::now()->subHour();
        $exists = self::where('ip', $ip)
            ->where('created_at', '>=', $oneHourAgo)
            ->exists();

        if ($exists) {
            return;
        }

        // Mengambil data lokasi berdasarkan IP
        $endpoint = env('ENDPOINT_IP_API') . $ip;
        $response = Http::get($endpoint);
        $data = $response->successful() ? $response->json() : ['status' => 'failed'];

        $country = $data['status'] === 'success' ? $data['country'] : 'Unknown';
        $city = $data['status'] === 'success' ? $data['city'] : 'Unknown';

        // Deteksi informasi user
        $agent = new Agent();
        $os = $agent->platform();
        $browser = $agent->browser();
        $device = $agent->deviceType();
        $isRobot = $agent->isRobot();

        // Jika bukan robot, simpan ke database
        if (!$isRobot) {
            self::create([
                'ip' => $ip,
                'os' => $os,
                'browser' => $browser,
                'device_type' => $device,
                'country' => $country,
                'city' => $city,
            ]);

            // Simpan ke cache agar tidak duplikat dalam 1 jam
            Cache::put($cacheKey, true, now()->addHour());
        }
    }

    public static function getVisitorOs($start, $end): Collection
    {
        return self::select(
            'os',
            DB::raw('count(*) as count')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('os')
            ->get();
    }

    public static function getCountries($start, $end): Collection
    {
        return self::select(
            'country',
            DB::raw('count(*) as count')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('country')
            ->get();
    }

    public static function getCities($start, $end): Collection
    {
        return self::select(
            'city',
            DB::raw('count(*) as count')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('city')
            ->get();
    }
}
