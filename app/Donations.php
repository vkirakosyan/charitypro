<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donations extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'donations';
    protected $fillable = [
        'name',
        'description',
        'is_blocked',
        'images',
        'user_id',
        'cat_id',
        'phone'
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")->paginate($perPage);
    }

    public static function getNotBlocked($catId)
    {
        $where = [
            ['is_blocked', false]
        ];

        if ($catId) {
            $where[] = ['cat_id' , $catId];
        }

        return self::select(['donations.*', 'users.name as user_name', 'users.img as user_img', 'users.email as user_email'])
            ->leftJoin('users', 'donations.user_id', '=', 'users.id')
            ->where($where)
            ->orderBy('donations.created_at', 'DESC')
            ->paginate();
    }
}
