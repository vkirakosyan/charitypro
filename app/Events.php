<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class Events extends Model
{
   protected $table = 'events';
   protected $fillable = [
        'title',
        'description',
        'user_id',
        'city_id',
        'details_location',
        'time',
        'img'
    ];

    public static function getByFilter($title = '', $cityId = 0, $dateFrom = '', $dateTo = '')
    {
        $sql = self::select(['events.*', 'cities.name as city_name'])
            ->leftJoin('cities', 'events.city_id', '=', 'cities.id');

        if ($title) {
            $sql->where('events.title', 'ILIKE', "%$title%");
        }

        if ((int) $cityId) {
            $sql->where('events.city_id', '=', $cityId);
        }

        if ($dateFrom && Carbon::createFromFormat('Y-m-d', $dateFrom) !== false && !$dateTo) {
            $sql->where('events.time', '>=', $dateFrom);
        }

        if (!$dateFrom && $dateTo && Carbon::createFromFormat('Y-m-d', $dateTo) !== false) {
            $sql->where('events.time', '<=', $dateTo);
        }

        if ($dateFrom && Carbon::createFromFormat('Y-m-d', $dateFrom) !== false && $dateTo && Carbon::createFromFormat('Y-m-d', $dateTo) !== false) {
            $sql->whereBetween('events.time', [$dateFrom, $dateTo]);
        }

        if(!$dateFrom && !$dateTo && !$title && !(int) $cityId)
        {
            $now = Carbon::now(config('app.timezone'));
            $page   = Input::get('page', 1);
            $limit  = 5;
            $offset = $page == 0 ? ($page) * $limit : ($page-1) * $limit;

 $sql = <<<SQL
select
    *,
    count( items.id ) over() count_items
from
    (
        (
            select
                events.*,
                cities.name as city_name
            from
                events
            left join cities on
                events.city_id = cities.id
            where
                events.time >= '$now'
            order by
                "time" asc
        )
union all(
        select
            events.*,
            cities.name as city_name
        from
            events
        left join cities on
            events.city_id = cities.id
        where
            events.time < '$now'
        order by
            "time" desc
    )
    ) items limit $limit offset $offset
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

        $sql->orderBy('events.time', 'desc');

        return $sql->paginate(5);
    }

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")->paginate($perPage);
    }

    public static function getById($itemId)
    {
        $userId    = \Auth::id() ? \Auth::id() : 0;
        $userGoes  = "(case when event_goings.user_id = {$userId} then 1 else - 1 end) as user_goes, (select count(*) from event_goings where event_id = $itemId) as count_goes";
        $userInterested = "(case when event_interested.user_id = {$userId} then 1 else - 1 end) as user_interested, (select count(*) from event_interested where event_id = $itemId) as count_interested";
        $viewsCount = "(select count(*) from event_views where event_id = $itemId) as count_views";

        $data = self::select(['events.*', 'users.name as user_name', 'users.email as user_email', \DB::raw($userGoes), \DB::raw($userInterested), \DB::raw($viewsCount)])
            ->leftJoin('users', 'events.user_id', '=', 'users.id')
            ->leftJoin('event_goings', 'events.id', '=', 'event_goings.event_id')
            ->leftJoin('event_interested', 'events.id', '=', 'event_interested.event_id')
            ->leftJoin('event_views', 'events.id', '=', 'event_views.event_id')
            ->where('events.id', $itemId)
            ->first();

        if (is_null($data)) {
            return null;
        }

        $data->going_users      = EventGoing::getUsers($itemId)->going_users;
        $data->interested_users = EventInterested::getUsers($itemId)->interested_users;

        return $data;
    }

    public static function getUpcoming()
    {
        // return self::where('time', '>', Carbon::now(config('app.timezone')))
        //     ->orderBy('time', 'ASC')
        //     ->first();
        return self::orderBy('updated_at', 'desc')->limit(4)->get();
    }
}
