<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventInterested extends Model
{
   protected $table = 'event_interested';
   protected $fillable = [
        'user_id',
        'event_id',
        'interested'
    ];

    public static function add($userId, $addOrCancle, $eventId)
    {
        $item = self::where([
            ['user_id', $userId],
            ['event_id', $eventId]
        ]);

        if ($data = $item->first()) {
            $item->delete();
        } elseif($addOrCancle) {
            self::insert([
                'user_id'    => $userId,
                'event_id'   => $eventId,
                'interested' => $addOrCancle,
            ]);
        }

        return self::getUsers($eventId);
    }

    public static function getUsers($eventId)
    {
        $sql = <<<SQL
select
    count(*) as count_interested,
    array_to_json(array_agg(row_to_json(row( u.name, case when u.img is not null then u.img else case when u.gender = 'M' then 'male.png' else 'female.png' end end )))) as interested_users
from
    event_interested ei
left join users u on
    u.id = ei.user_id
where
    ei.event_id = $eventId
SQL;

        $data        = \DB::select(\DB::raw($sql));
        $defaultData = new \StdClass();

        $defaultData->count_interested = 0;
        $defaultData->interested_users = json_encode([]);

        return $data ? $data[0] : $defaultData;
    }
}
