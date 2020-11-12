<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Images;
use App\Stories;
class ImagesController extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    public function index(){
         $id            = \Auth::id();
        $items = Stories::getByFilter([] ,1, 6,0);
        $images=[
             'isAdmin'   => $this->isAdmin(),
             'user_data' => $this->userData(),
             'images'=>Images::orderBy('id','desc')->get(),
             'items'       => $items['data'],
             'userId'     => $id ? $id : 0,
        ];

        return \View::make('images', $images);
    }
    public function show($id){
        $page=1;
         $data=Images::findOrFail($id);
         $all_images=json_decode($data['images']);
        $count=count(array_chunk($all_images,12));
         if(isset($_GET['page']) && $_GET['page'] >=1) {
            $page=$_GET['page'];

         }
         if($count>1){
            $all_images=array_chunk($all_images,12)[$page-1];
        }
            $id            = \Auth::id();
            $images=[
             'isAdmin'   => $this->isAdmin(),
             'user_data' => $this->userData(),
             'images' => $all_images,
             'count' =>$count,
             'title' =>$data['title'],
             'userId'     => $id ? $id : 0,
        ];
        return \View::make('images_more', $images);
    }

}

