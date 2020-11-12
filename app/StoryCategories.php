<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoryCategories extends Model
{
    /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'story_categories';
    protected $fillable = [
        'name'
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }

    // public static function findByCategory($keyword, $perPage = null)
    // {
    //     return self::where('name', 'ILIKE', "%$keyword%")
    //         ->paginate($perPage);
    // }
}
