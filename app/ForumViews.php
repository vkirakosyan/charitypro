<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ForumViews extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'forum_views';

    public static function addView(int $forumId, string $ip)
    {
        $now = Carbon::now(config('app.timezone'));
        $item = self::where([
            ['forum_id', $forumId],
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
                    'forum_id'   => $forumId,
                    'ip'         => $ip,
                    'created_at' => $now,
                    'updated_at' => $now
                ]
            ]);
        }
    }
}
