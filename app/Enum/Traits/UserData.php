<?php

namespace App\Enum\Traits;

trait UserData
{
    private function userData()
    {
        $user = \Auth::user();
        $data = new \StdClass();

        $data->img    = '';
        $data->id     = 0;
        $data->name   = '';
        $data->email  = '';
        $data->gender = '';

        if (!is_null($user)) {
            $data->img    = $user->img;
            $data->id     = $user->id;
            $data->name   = $user->name;
            $data->email  = $user->email;
            $data->gender = $user->gender;
        }

        return $data;
    }
}