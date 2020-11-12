<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\{Events, JobCities};
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class EventsController extends Controller implements DBConsts
{
    public function __construct()
    {
        $this->middleware('checkAdmin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $keyword    = $request->get('search');
        $event   = !empty($keyword) ? Events::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : Events::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);
        // $categories = EventInterested::get();

        return view('admin.events.index', compact('event', 'categories', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        // $categories = EventInterested::get();
        $cities     = JobCities::get();

        return view('admin.events.create', compact('categories', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);

        $postData            = $request->all();
        $postData['img']     = $this->fileUpload($request);
        $postData['user_id'] = \Auth::id();

        Events::create($postData);

        return redirect('admin/events')->with('flash_message', 'Event added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $events = Events::findOrFail($id);
        return view('user.event.show', compact('events'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $events  = Events::findOrFail($id);
        // $categories = EventInterested::get();
        $cities     = JobCities::get();
        return view('admin.events.edit', compact('events', 'categories', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);

        $events  = Events::findOrFail($id);
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

        return redirect('admin/events')->with('flash_message', 'Event updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        Events::destroy($id);

        return redirect('admin/events')->with('flash_message', 'Event deleted!');
    }

    public function fileUpload(Request $request)
    {

        $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = Input::file('img');

        $input['imagename'] = uniqid(time()) . '.' . $images->getClientOriginalExtension();

        $destinationPath = public_path(ImgPath::EVENTS);
        Image::configure(array('driver' => 'imagick'));
        $img = Image::make($images);
        $width = $img->width();
        $height = $img->height();
        if($width == $height)
        {
            $img->resize(500, 500)->save($destinationPath.'/'.$input['imagename']);
        }
        else if($width > $height)
        {   
            $divisor = $width / 500;
            $img->resize(500, floor($height / $divisor))->save($destinationPath.'/'.$input['imagename']);
        }
        else
        {
            $divisor = $height / 500;
            $img->resize(floor($width / $divisor), 500)->save($destinationPath.'/'.$input['imagename']);
        }

        return $input['imagename'];
    }
}
