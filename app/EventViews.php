<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventViews extends Model
{
    protected $table = 'event_views';

    public static function addView(int $eventId, string $ip)
    {
        $now = Carbon::now(config('app.timezone'));
        $item = self::where([
            ['event_id', $eventId],
            ['ip', $ip]
        ]);

        $checkEmpty = $item->first();
        $dateDiff   = $checkEmpty ? $now->diff($item->orderBy('created_at', 'desc')->first()->created_at) : 0;

        if ($dateDiff !== 0) {
            $dateDiff = $dateDiff->h + ($dateDiff->days > 0 ? 1 : 0);

        }

        if (!$checkEmpty || $dateDiff > 0) {
            self::insert([
                [
                    'event_id'   => $eventId,
                    'ip'         => $ip,
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            ]);
        }
    }
}
