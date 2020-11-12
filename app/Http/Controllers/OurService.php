<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SuggestedServices;

class OurService extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;
    
    public function home($serviceId = 0)
    {
        $id            = \Auth::id();
        $scriptsStyles = [
            'styles' => [
                'css/ourService.css'
            ],
            'scripts' => [
            ],
            'userId'    => $id ? $id : 0,
            'serviceId' => $serviceId,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];

        return \View::make('ourService', $scriptsStyles);
    }

    public function lastTwoServices()
    {
        return SuggestedServices::getLast2();
    }

    public function allServices()
    {
        return SuggestedServices::getAll();
    }

    public function getById(Request $request)
    {
        $id = $request->get('id');

        if (!$id) {
            return [
                'error' => 'id required'
            ];
        }

        $data = SuggestedServices::select('id', 'title', 'description', 'img')->find($id);

        return $data ? $data : [];
    }
}
