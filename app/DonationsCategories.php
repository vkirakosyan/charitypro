<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DonationsCategories extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
    public $timestamps = false;
    protected $table = 'donations_categories';
    protected $fillable = [
        'name',
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }
}
