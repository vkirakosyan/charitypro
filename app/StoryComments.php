<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoryComments extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'story_comments';
    protected $fillable = [
        'story_id',
        'user_id',
        'description'
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }

    public static function getCommentsByStoryId($storyId, $commentId = 0) 
    {
        $andWhere = '';

        if ($commentId) {
            $andWhere = 'and sc.id = ' . $commentId;
        }

        $sql = <<<SQL
select
    sc.*,
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
    story_comments as sc
left join users as u on
    u.id = sc.user_id
left join(
        select
            scl.comment_id,
            sum(( scl."like" = true )::int ) as likes_count,
            sum(( scl."like" = false )::int ) as dislikes_count
        from
            story_comment_likes scl
        group by
            scl.comment_id
    ) as l on
    l.comment_id = sc.id
where
    sc.story_id = $storyId
    $andWhere
order by
    sc.created_at
SQL;

        return \DB::select(\DB::raw($sql));
    }

    public static function getCommentsStartedWith($storyId, $startedCommentId)
    {
        $sql = <<<SQL
select
    sc.*,
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
    story_comments as sc
left join users as u on
    u.id = sc.user_id
left join(
        select
            scl.comment_id,
            sum(( scl."like" = true )::int ) as likes_count,
            sum(( scl."like" = false )::int ) as dislikes_count
        from
            story_comment_likes scl
        group by
            scl.comment_id
    ) as l on
    l.comment_id = sc.id
where
    sc.story_id = $storyId
    and sc.id > $startedCommentId
SQL;

        return \DB::select(\DB::raw($sql));
    }
}
