<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobsCategories extends Model
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    public $timestamps = false;
    protected $table = 'job_categories';
    protected $fillable = [
        'name',
        'img'
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }

    public static function findByCategory($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }
}
