<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class Visitor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function saveVisitor()
    {
        $sessionLength = 60 * 60; // 1 jam
        $ip = Request::ip();
        $session = Request::session();

        $lastVisited = $session->get('last_visited');
        $currentTime = time();

        if ($lastVisited && ($currentTime - $lastVisited < $sessionLength)) {
            $exists = self::where('ip', $ip)
                ->where('created_at', '>=', date('Y-m-d H:i:s', $lastVisited))
                ->exists();

            if ($exists) {
                return;
            }
        }

        $agent = new Agent();
        $os = $agent->platform();
        $browser = $agent->browser();
        $device = $agent->deviceType();
        $isRobot = $agent->isRobot();

        if ($isRobot === false) {
            self::create([
                'ip' => $ip,
                'os' => $os,
                'browser' => $browser,
                'device_type' => $device,
            ]);

            $session->put('last_visited', $currentTime);
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
}
