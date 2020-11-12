<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WhatTheySay extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
   protected $table = 'what_they_say';
   protected $fillable = [
        'img',
        'name',
        'description',
        'profession'
    ];

    public static function findByName($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }
}
