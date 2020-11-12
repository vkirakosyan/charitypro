<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumCommentsLikes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'forum_comments_likes';
    protected $fillable = [
        'comment_id',
        'user_id',
        'like'
    ];

    public static function add(int $userId, bool $likeOrDislike, int $commentId)
    {
        $item = self::where([
            ['user_id', $userId],
            ['comment_id', $commentId]
        ]);

        if ($data = $item->first()) {
            if ($data->like === $likeOrDislike) {
                $item->delete();
            } else {
                $item->update([
                    'like'       => $likeOrDislike,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        } else {
            self::insert([
                'user_id'    => $userId,
                'comment_id' => $commentId,
                'like'       => $likeOrDislike,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $sql = <<<SQL
select
    (
        case
            when sum(( fcl."like" = true )::int ) is null then 0
            else sum(( fcl."like" = true )::int )
        end
    ) as likes_count,
    (
        case
            when sum(( fcl."like" = false )::int ) is null then 0
            else sum(( fcl."like" = false )::int )
        end
    ) as dislikes_count
from
    forum_comments_likes fcl
where
    fcl.comment_id = $commentId
SQL;

        return \DB::select(\DB::raw($sql))[0];
    }
}
