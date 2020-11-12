<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCities extends Model
{
    public $timestamps = false;
    protected $table = 'cities';
    protected $fillable = [
        'name',
    ];
}
