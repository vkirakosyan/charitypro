<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsConditions extends Controller
{
	use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;
    
	public function home()
    {
    	$id = \Auth::id();
        $scriptsStyles = [
            'styles' => [
                URL('/css/termsConditions.css')
            ],
            'scripts' => [
             
            ],
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];

        return \View::make('termsconditions', $scriptsStyles);
    }
}
