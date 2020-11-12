<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WhatTheySay;

class AboutUs extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    public function getAllData()
    {
        $result = WhatTheySay::get();

        return $result;
    }

    public function home()
    {
        $id            = \Auth::id();
        $scriptsStyles = [
            'styles' => [
             /*   URL('/css/aboutUs.css'),*/
                URL('/css/slick.css'),
                URL('/css/slick-theme.css')
            ],
            'scripts' => [
           /*     URL('/js/aboutUs.js'),*/
                URL('/js/slick.min.js')
            ],
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];

        return \View::make('aboutus', $scriptsStyles);
    }
    public function terms(){
          $id            = \Auth::id();
        $scriptsStyles = [
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];
        return \View('termsconditions',$scriptsStyles);
    }
}
