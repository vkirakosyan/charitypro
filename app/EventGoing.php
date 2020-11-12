<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventGoing extends Model
{
    protected $table = 'event_goings';
    protected $fillable = [
        'user_id',
        'event_id',
        'participation'
    ];

    public $timestamps = false;

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
                'user_id'       => $userId,
                'event_id'      => $eventId,
                'participation' => $addOrCancle,
            ]);
        }

        return self::getUsers($eventId);
    }

    public static function getUsers($eventId)
    {
        $sql = <<<SQL
select
    count(*) as count_goings,
    array_to_json(array_agg(row_to_json(row( u.name, case when u.img is not null then u.img else case when u.gender = 'M' then 'male.png' else 'female.png' end end )))) as going_users
from
    event_goings eg
left join users u on
    u.id = eg.user_id
where
    eg.event_id = $eventId
SQL;

        $data        = \DB::select(\DB::raw($sql));
        $defaultData = new \StdClass();

        $defaultData->count_goings = 0;
        $defaultData->going_users  = json_encode([]);

        return $data ? $data[0] : $defaultData;
    }
}
