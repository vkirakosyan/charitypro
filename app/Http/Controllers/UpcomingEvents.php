<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{JobCities, Events as MyModel, EventViews, EventGoing, EventInterested};
use App\Enum\ImgPath;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

class UpcomingEvents extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    public function home(Request $request)
    {
        $dbData = $this->getByFilter($request);

        if (is_object($dbData)) {
            $dbData = $dbData->toArray();
        }

        $data   = array_merge($this->requiredData(), [
            'data'       => json_decode(json_encode($dbData['data'])),
            'pagination' => [
                'current_page' => $dbData['current_page'],
                'total'        => $dbData['total'],
                'per_page'     => $dbData['per_page']
            ]
        ]);

        return \View::make('upcomingEvents', $data);
    }

    private function requiredData()
    {
        $id = \Auth::id();

        return [  
            'styles' => [
                'css/upcomingEvents.css'
            ],
            'scripts' => [
                'js/upcomingEvents.js'
            ],
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
            'cities'    => JobCities::get(),
            'title'     => Input::get('title'),
            'city_id'   => Input::get('city_id', 0),
            'date_from' => Input::get('from_date'),
            'date_to'   => Input::get('to_date'),
            'pageTitle' => 'CharityPro - Իրադարձություններ',
        ];
    }

    private function getByFilter(Request $request)
    {
        $title    = $request->get('title');
        $cityId   = $request->get('city_id', 0);
        $dateFrom = $request->get('from_date');
        $dateTo   = $request->get('to_date');
        return MyModel::getByFilter($title, $cityId, $dateFrom, $dateTo);
    }

    public function details($itemId, Request $request)
    {
        $ip = array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? mb_split(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]  : $request->getClientIp();

        if (!$itemId) {
            abort(404);
        }

        EventViews::addView($itemId, $ip);

        $item = MyModel::getById($itemId);

        if (is_null($item)) {
            abort(404);
        }

        $data = array_merge($this->requiredData(), [
            'c_item'    => $item,
            'prevUrl'   => \URL::previous(),
            'pageTitle' => $item->title,
            'og'        => [
                'title' => $item->title,
                'url'   => \URL('upcoming_events/details', $item->id),
                'image' => \URL(ImgPath::EVENTS . $item->img)
            ]
        ]);

        return \View::make('upcomingEvents', $data); 
    }

    public function addGoing(Request $request)
    {
        $userId    = \Auth::id();
        $validator = \Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        if ($userId) {
            $addOrCancle = intval($request->get('add_or_cancle')) === 1;
            $eventId     = (int) $request->get('event_id');

            return json_encode(EventGoing::add($userId, $addOrCancle, $eventId));
        } else {
            return [
                'error'   => true,
                'message' => 'Please'
            ];
        }
    }

    public function addInterested(Request $request)
    {
        $userId    = \Auth::id();
        $validator = \Validator::make($request->all(), [
            'event_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        if ($userId) {
            $addOrCancle = intval($request->get('add_or_cancle')) === 1;
            $eventId     = (int) $request->get('event_id');

            return json_encode(EventInterested::add($userId, $addOrCancle, $eventId));
        } else {
            return [
                'error'   => true,
                'message' => 'Please'
            ];
        }
    }

    public function getEventsByUserId ()
    {
        $page=1;
           $id=\Auth::id();
        $result['userId'] = $id;
        $result['isAdmin']   = $this->isAdmin();
        $result['user_data'] = $this->userData();
        $result['events'] = MyModel::select(['events.*', 'cities.name as city_name'])->where('user_id','=', $id)
            ->leftJoin('cities', 'cities.id', '=', 'events.city_id')
            ->latest()->get(); 
        $result['city']   = JobCities::get();  
        $result['events'] = json_decode(json_encode($result['events']), false);
        foreach ($result['events'] as  $value) {
            $value= json_decode(json_encode($value), true);
            array_shift($result['events']);
            array_push($result['events'],$value);
        }
        $count=count(array_chunk($result['events'] ,5));
           if(isset($_GET['page']) && $_GET['page'] >=1) {
            $page=$_GET['page'];
         }
          if($count>1){
          $result['events']=array_chunk($result['events'],5)[$page-1];
      }
         $result['count']=$count;
         return \View::make('user.event.index', $result);
    }

    public function show($id){
         $events = MyModel::findOrFail($id);
         $city      = JobCities::get();
        $result['events']=$events;
        $result['city']=$city;
             $id            = \Auth::id();
        $MyUser = [
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];
       return \View::make('user.event.show', $result,$MyUser);

    }
      public function edit($id)
    {
        $userId = \Auth::id();
        $data   = [
            'userId'     => $userId ? $userId : 0,
            'isAdmin'    => $this->isAdmin(),
            'user_data'  => $this->userData(),
            'events'     => MyModel::findOrFail($id),
            'categories' => EventInterested::get(),
            'cities'     => JobCities::get(),
        ];

        return view('user.event.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);
        $events  = MyModel::findOrFail($id);
        $postData   = $request->all();
        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }
        $postData['is_blocked'] = $request->has('is_blocked') ? 1 : 0;

        if (!empty($postData['img'])) {
            $postData['img'] = $this->fileUpload($request);
            if (!empty($image_uploaded)) {
                unlink(public_path(ImgPath::EVENTS . $image_uploaded));
            }
        } else {
            if (!empty($image_uploaded)) {
                $postData['img'] = $image_uploaded;
            }
            else {
                unset($postData['img']);
            }
        }

        $events->update($postData);

        return redirect('events')->with('flash_message', 'Event updated!');
    }

    public function getEvent(Request $request)
    {
        $id                   = json_decode($request->get('id'));
        $result               = [];
        $result['event']      = MyModel::where('id','=', $id)->get();
        $result['city']       = JobCities::get();
        return $result;
    }

    public function create(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title'            => 'required',
            'description'      => 'required',
            'details_location' => 'required',
            'time'             => 'required',
            'city_id'          => 'required',
            'img'              => 'required',
        ]);

        if ($validator->fails()) {

            return [
                'errors' => $validator->errors()
            ];
        }

        $postData = $request->all();
        $img      = $this->fileUpload($request);

        if (is_array($img)) {
            return $img;
        }

        $postData['img']     = $img;
        $postData['user_id'] = \Auth::id();
        unset($postData['_token']);
        $Events              = MyModel::create($postData);

        return [
            'status' => true,
            'item'   => $Events
        ];
    }

    private function fileUpload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        $images          = Input::file('img');
        $imageName       = uniqid(time()) . '.' . $images->getClientOriginalExtension();
        $destinationPath = public_path(ImgPath::EVENTS);
        Image::configure(array('driver' => 'imagick'));
        $img = Image::make($images);
        $width = $img->width();
        $height = $img->height();
        if($width == $height)
        {
            $img->resize(500, 500)->save($destinationPath.'/'.$imageName);
        }
        else if($width > $height)
        {   
            $divisor = $width / 500;
            $img->resize(500, floor($height / $divisor))->save($destinationPath.'/'.$imageName);
        }
        else
        {
            $divisor = $height / 500;
            $img->resize(floor($width / $divisor), 500)->save($destinationPath.'/'.$imageName);
        }

        return $imageName;
    }

/*    public function deleteEventsByUserId(Request $request)
    {
        $id  = $request->get('id');
        return MyModel::where('id','=', $id)->delete();
    }*/
    public function deleteEventsByUserId($id)
    {
        $userId = \Auth::id();
        $myevent=MyModel::select('user_id')->where('id',$id)->first();
        if($userId == $myevent['user_id']){
         MyModel::destroy($id);
        }
        return redirect()->back();
    }

    public function updateEventAccount(Request $request)
    {
        $postData      = $request->all();
        $validator = \Validator::make($postData, [
            'title'            => 'required',
            'description'      => 'required',
            'details_location' => 'required',
            'time'             => 'required',
            'city_id'          => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }
        if(isset($postData['img'])){
            $img             = $this->fileUpload($request);
            $postData['img'] = $img;
        }

        $id = $postData['id'];
        unset($postData['_token']);
        unset($postData['id']);
        $postData['user_id'] = \Auth::id();
        $jobs = MyModel::where('id','=', $id)->update($postData);

        return [
            'status' => true
        ];
    }
}
