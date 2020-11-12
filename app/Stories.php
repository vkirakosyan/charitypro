<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    protected $table = 'stories';
    protected $fillable = [
        'user_id',
        'cat_id',
        'title',
        'description',
        'youtube_id',
        'images'
    ];

    public static function getByFilter(array $filters = [] , $page = 1, $limit = 8, $offset = 0)
    {
        $page   = \Input::get('page', $page ? $page : 1);
        $limit  = $limit ? $limit : 8;
        $offset = $offset ? $offset : ($page - 1) * $limit;
        $where  = '';

        if (count($filters)) {
            if ($filters['cat_id']) {
                $where = "where s.cat_id = " . $filters['cat_id'];
            }
        }

        $sql = <<<SQL
select
    count( s.id ) over() as count_items,
    s.*,
    u."name" as user_name,
    (case when u.img is not null then u.img else case when u.gender = 'M' then 'male.png' else 'female.png' end end) as user_img,
    (
        case
            when sum( sc.comments_count ) is null then 0
            else sum( sc.comments_count )
        end
)::int as comments_counts,
    (
        case
            when sum( sc.likes_count ) is null then 0
            else sum( sc.likes_count )
        end
    )::int as story_likes_count,
    (
        case
            when sum( sc.dislikes_count ) is null then 0
            else sum( sc.dislikes_count )
        end
    )::int as story_dislikes_count
from
    stories s
left join users as u on
    u.id = s.user_id
left join(
        select
            story_id,
            count( story_comments.id ) as comments_count,
            sum(( story_comment_likes."like" = true )::int ) as likes_count,
            sum(( story_comment_likes."like" = false )::int ) as dislikes_count,
            users.name username,
            users.img user_img
        from
            story_comments
        left join story_comment_likes on
            story_comments.id = story_comment_likes.comment_id
        left join users on
            users.id = story_comments.user_id
        group by
            users.name,
            users.img,
            story_comments.id
        order by
            story_comments.created_at asc
    ) sc on
    s.id = sc.story_id
    $where
group by
    s.id,
    u.id
order by
    s.created_at DESC limit $limit offset $offset
SQL;

        $data  = \DB::select(\DB::raw($sql));
        $total = count($data) > 0 ? $data[0]->count_items : 0;

        return [
            'current_page' => $page,
            'data'         => $data,
            'total'        => $total,
            'per_page'     => $limit,
        ];
    }

    public static function getStoriDetailes($storyId)
    {
        return self::select(['stories.*', 'users.name as user_name', \DB::raw("(case when users.img is not null then users.img else case when users.gender = 'M' then 'male.png' else 'female.png' end end) as user_img")])
            ->leftJoin('users', 'stories.user_id', '=', 'users.id')
            ->where('stories.id', $storyId)
            ->first();
    }

    public static function getSuccessStory()
    {
        $story = Settings::getSuccessStory();
        if($story){
            return self::getStoriDetailes($story);
        }
        return null;
    }
}
