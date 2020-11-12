<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
{
   protected $table = 'forums';
   protected $fillable = [
        'user_id',
        'title',
        'description',
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")->paginate($perPage);
    }

    public static function getByFilter($page = 1, $limit = 15, $offset = 0)
    {
        $page   = $page ? $page : 1;
        $limit  = $limit ? $limit : 15;
        $offset = $offset ? $offset : ($page - 1) * $limit;

        $sql = <<<SQL
select
    f.*,
    case
        when fv.views_count is null then 0
        else fv.views_count
    end,
    (case when u.img is not null then u.img else case when u.gender = 'M' then 'male.png' else 'female.png' end end) as avatar,
    u."name" as user_name,
    case
        when fc.comments_count is null then 0
        else fc.comments_count
    end,
    count( f.id ) over() count_items
from
    forums f
left join users u on
    u.id = f.user_id
left join(
        select
            forum_id,
            count( id ) comments_count
        from
            forum_comments
        group by
            forum_id
    ) fc on
    fc.forum_id = f.id
left join(
        select
            forum_id,
            count( ip ) views_count
        from
            forum_views
        group by
            forum_id
    ) fv on
    fv.forum_id = f.id
order by
    f.created_at DESC limit $limit offset $offset
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
}
 