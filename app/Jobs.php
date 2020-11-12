<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
   protected $table = 'jobs';
   protected $fillable = [
        'user_id',
        'title',
        'description',
        'img',
        'number',
        'email',
        'cat_id',
        'city_id'
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")->paginate($perPage);
    }

    public static function getByFilter(\stdClass $filters)
    {
        $sql = self::select(['jobs.*', 'cities.name as city_name', 'job_categories.name as category_name', 'users.name as username'])
            ->leftJoin('job_categories', 'job_categories.id', '=', 'jobs.cat_id')
            ->leftJoin('users', 'users.id', '=', 'jobs.user_id')
            ->leftJoin('cities', 'cities.id', '=', 'jobs.city_id');

        if (property_exists($filters, 'cat_id') && $filters->cat_id) {
                $sql->where('cat_id','=', $filters->cat_id);
        }

        if (property_exists($filters, 'city_id') && $filters->city_id) {
                $sql->where('city_id','=', $filters->city_id);
        }

        if (property_exists($filters, 'title')) {
            $sql->where('title', 'ILIKE', "%" . $filters->title ."%");
        }

        return $sql->paginate();
    }
}
