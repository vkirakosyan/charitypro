<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'img',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function findByNameAndEmail($keyword, $perPage = null)
    {
        return self::where('name', 'ILIKE', "%$keyword%")
            ->orWhere('email', 'ILIKE', "%$keyword%")
            ->paginate($perPage);
    }

    public function getImgAttribute($value)
    {
        if (!is_null($value) && strlen($value) > 0) {
            return $value;
        } else {
            return $this->attributes['gender'] == 'M' ? 'male.png' : 'female.png';
        }
    }
}
