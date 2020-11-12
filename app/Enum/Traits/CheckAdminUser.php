<?php

namespace App\Enum\Traits;

use App\Enum\UserRole;

trait CheckAdminUser
{
    public function isAdmin()
    {
        $user = \Auth::user();

        if (is_null($user)) {
            return false;
        }

        return $user->hasRole(UserRole::ADMINISTRATOR);
    }
}