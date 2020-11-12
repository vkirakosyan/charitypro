<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForumComments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    // public $timestamps = false;
    protected $table = 'forum_comments';
    protected $fillable = [
        'forum_id',
        'user_id',
        'comment',
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }

    public static function getCommentsById($forumId, $commentId = 0) 
    {
        $andWhere = '';

        if ($commentId) {
            $andWhere = 'and fc.id = ' . $commentId;
        }

        $sql = <<<SQL
select
    fc.*,
    (case when u.img is not null then u.img else case when u.gender = 'M' then 'male.png' else 'female.png' end end) as avatar,
    u."name" as user_name,
    case
        when l.likes_count is null then 0
        else l.likes_count
    end,
    case
        when l.dislikes_count is null then 0
        else l.dislikes_count
    end
from
    forum_comments as fc
left join users as u on
    u.id = fc.user_id
left join(
        select
            fcl.comment_id,
            sum(( fcl."like" = true )::int ) as likes_count,
            sum(( fcl."like" = false )::int ) as dislikes_count
        from
            forum_comments_likes fcl
        group by
            fcl.comment_id
    ) as l on
    l.comment_id = fc.id
where
    fc.forum_id = $forumId
    $andWhere
order by
    fc.created_at
SQL;

        return \DB::select(\DB::raw($sql));
    }

    public static function getCommentsStartedWith($forumId, $startedCommentId)
    {
        $sql = <<<SQL
select
    fc.*,
    (
        case
            when u.img is not null then u.img
            else case
                when u.gender = 'M' then 'male.png'
                else 'female.png'
            end
        end
    ) as avatar,
    u."name" as user_name,
    case
        when l.likes_count is null then 0
        else l.likes_count
    end,
    case
        when l.dislikes_count is null then 0
        else l.dislikes_count
    end
from
    forum_comments as fc
left join users as u on
    u.id = fc.user_id
left join(
        select
            fcl.comment_id,
            sum(( fcl."like" = true )::int ) as likes_count,
            sum(( fcl."like" = false )::int ) as dislikes_count
        from
            forum_comments_likes fcl
        group by
            fcl.comment_id
    ) as l on
    l.comment_id = fc.id
where
    fc.forum_id = $forumId
    and fc.id > $startedCommentId
SQL;

        return \DB::select(\DB::raw($sql));
    }
}
