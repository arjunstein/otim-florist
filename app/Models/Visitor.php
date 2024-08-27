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

    // landing page
    public static function saveVisitor()
    {
        $ip = Request::ip();
        $session = Request::session();

        if (!$session->has('visitor_logged')) {
            $agent = new Agent();
            $os = $agent->platform();
            $browser = $agent->browser();
            $device = $agent->deviceType();
            $isRobot = $agent->isRobot();

            if (!$isRobot) {
                self::create([
                    'ip' => $ip,
                    'os' => $os,
                    'browser' => $browser,
                    'device_type' => $device,
                ]);

                $session->put('visitor_logged', true);
            }
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
