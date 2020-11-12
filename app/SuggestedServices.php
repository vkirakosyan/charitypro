<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DBConsts;

class SuggestedServices extends Model implements DBConsts
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'suggested_services';
    protected $fillable = [
        'title',
        'description',
        'img'
    ];

    public static function getLast2()
    {
        return self::orderBy('created_at', self::DESC)->limit(2)->get();
    }

    public static function getAll()
    {
        return self::orderBy('created_at', self::DESC)->paginate(10);
    }

    public static function findByTitile($keyword, $perPage = null)
    {
        return self::where('title', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }
}
