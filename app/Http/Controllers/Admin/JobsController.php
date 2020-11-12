<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\{Jobs, JobsCategories, JobCities};
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

class JobsController extends Controller implements DBConsts
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
        $job   = !empty($keyword) ? Jobs::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : Jobs::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);
        $categories = JobsCategories::get();

        return view('admin.jobs.index', compact('job', 'categories', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $categories = JobsCategories::get();
        $cities     = JobCities::get();

        return view('admin.jobs.create', compact('categories', 'cities'));
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
        $jobs                = Jobs::create($postData);
        return redirect('admin/jobs')->with('flash_message', 'Job added!');
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
        $jobs = Jobs::findOrFail($id);

        return view('admin.jobs.show', compact('jobs'));
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
        $jobs  = Jobs::findOrFail($id);
        $categories = JobsCategories::get();
        $cities     = JobCities::get();
        return view('admin.jobs.edit', compact('jobs', 'categories', 'cities'));
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

        $jobs  = Jobs::findOrFail($id);
        $postData   = $request->all();

        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }
        $postData['is_blocked'] = $request->has('is_blocked') ? 1 : 0;

        if (!empty($postData['img'])) {
            $postData['img'] = $this->fileUpload($request);
            if (!empty($image_uploaded)) {
                unlink(public_path(ImgPath::JOBS . $image_uploaded));
            }
        } else {
            if (!empty($image_uploaded)) {
                $postData['img'] = $image_uploaded;
            }
            else {
                unset($postData['img']);
            }
        }

        $jobs->update($postData);

        return redirect('admin/jobs')->with('flash_message', 'Job updated!');
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
        Jobs::destroy($id);

        return redirect('admin/jobs')->with('flash_message', 'Job deleted!');
    }

    public function fileUpload(Request $request)
    {

        $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images          = Input::file('img');
        $input['imagename'] = uniqid(time()) . '.' . $images->getClientOriginalExtension();
        $destinationPath = public_path(ImgPath::JOBS);
        Image::configure(array('driver' => 'imagick'));
        $img = Image::make($images);
        $width = $img->width();
        $height = $img->height();
        if($width == $height)
        {
            $img->resize(300, 300)->save($destinationPath.'/'.$input['imagename']);
        }
        else if($width > $height)
        {   
            $divisor = $width / 300;
            $img->resize(300, floor($height / $divisor))->save($destinationPath.'/'.$input['imagename']);
        }
        else
        {
            $divisor = $height / 300;
            $img->resize(floor($width / $divisor), 300)->save($destinationPath.'/'.$input['imagename']);
        }

        return $input['imagename'];
    }
}
