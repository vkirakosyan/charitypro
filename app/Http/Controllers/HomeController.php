<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Events, Settings, Stories,SuggestedServices,WhatTheySay};
use App\Donations;
class HomeController extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Donations::where('is_blocked',true)->first()!=null) {
            $Donations = Donations::where('is_blocked',true)->first();
        }
        else{
            $Donations = Donations::OrderBy('created_at', 'desc')->first();
        }
        
        $id            = \Auth::id();
        $scriptsStyles = [
            'donations'=> $Donations,
            'styles' => [
                //'css/home.css',
                //'css/homeVideo.css',
                //'css/ddvideogallery.css'
            ],
            'scripts' => [
                'js/home.js',
                'js/user_data.js'
            ],
            'userId'        => $id ? $id : 0,
            'isAdmin'       => $this->isAdmin(),
            'user_data'     => $this->userData(),
            'last_event'    => Events::getUpcoming(),
            'offered_services' => SuggestedServices::latest()->first(),
            'success_story' => Stories::getSuccessStory(),
            'youtube_links' => array_reverse(Settings::getYoutubeLinks()),
            'what_they_say' => WhatTheySay::get(),
        ];
        return \View::make('home', $scriptsStyles);
    }
    public function video()
    {
        $id            = \Auth::id();
        $scriptsStyles = [
            'userId'        => $id ? $id : 0,
            'isAdmin'       => $this->isAdmin(),
            'user_data'     => $this->userData(),
            'success_story' => Stories::getSuccessStory(),
            'youtube_links' => array_reverse(Settings::getYoutubeLinks()),
        ];
        return \View::make('videos', $scriptsStyles);
    }
}
