<?php

namespace App\Http\Controllers;

use App\Enum\ImgPath;

use App\JobCities;
use App\Jobs as MyModel;
use App\JobsCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Intervention\Image\ImageManagerStatic as Image;

class Job extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;

    public function home()
    {
        $id = \Auth::id();
        $scriptsStyles = [
            // 'styles' => [
            //     URL('/css/job.css'),
            // ],
            'scripts' => [
                URL('/js/job_new.js'),
            //     URL('/js/masked-input.js')
            ],
            'userId' => $id ? $id : 0,
            'isAdmin' => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];

        return \View::make('job', $scriptsStyles);
    }

    public function getCategoriesAndCities()
    {
        $result = [];

        $result['categories'] = JobsCategories::get();
        $result['cities'] = JobCities::orderBy('name', 'ASC')->get();

        return $result;
    }

    public function getByFilter(Request $request)
    {
        $filters = json_decode($request->get('filters'));

        return MyModel::getByFilter($filters);
    }

    public function create(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'number' => 'required',
            'email' => 'required|email',
            'cat_id' => 'required',
            'city_id' => 'required',
            'img' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors(),
            ];
        }

        $postData = $request->all();
        $img = $this->fileUpload($request);

        if (is_array($img)) {
            return $img;
        }

        $postData['img'] = $img;
        $postData['user_id'] = \Auth::id();
        $jobs = MyModel::create($postData);

        return [
            'status' => true,
            'item' => $jobs,
        ];
    }

    private function fileUpload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors(),
            ];
        }

        $images = Input::file('img');
        $imageName = uniqid(time()) . '.' . $images->getClientOriginalExtension();
        $destinationPath = public_path(ImgPath::JOBS);
        Image::configure(array('driver' => 'imagick'));
        $img = Image::make($images);
        $width = $img->width();
        $height = $img->height();
        if ($width == $height) {
            $img->resize(300, 300)->save($destinationPath . '/' . $imageName);
        } else if ($width > $height) {
            $divisor = $width / 300;
            $img->resize(300, floor($height / $divisor))->save($destinationPath . '/' . $imageName);
        } else {
            $divisor = $height / 300;
            $img->resize(floor($width / $divisor), 300)->save($destinationPath . '/' . $imageName);
        }

        return $imageName;
    }

    public function getJobsByUserId()
    {
        $page = 1;
        $id = \Auth::id();
        $result['userId'] = $id;
        $result['isAdmin'] = $this->isAdmin();
        $result['user_data'] = $this->userData();
        $result['jobs'] = MyModel::where('user_id', '=', $id)->latest()->get();
        $result['city'] = JobCities::get();
        $result['categories'] = JobsCategories::get();
        $result['jobs'] = json_decode(json_encode($result['jobs']), false);
        foreach ($result['jobs'] as $value) {
            $value = json_decode(json_encode($value), true);
            array_shift($result['jobs']);
            array_push($result['jobs'], $value);
        }
        $count = count(array_chunk($result['jobs'], 4));
        if (isset($_GET['page']) && $_GET['page'] >= 1) {
            $page = $_GET['page'];
        }
        if ($count > 1) {
            $result['jobs'] = array_chunk($result['jobs'], 4)[$page - 1];
        }
        $result['count'] = $count;
        return \View::make('user.job.index', $result);
    }

    public function edit($id)
    {
        $userId = \Auth::id();
        $data   = [
            'userId'     => $userId ? $userId : 0,
            'isAdmin'    => $this->isAdmin(),
            'user_data'  => $this->userData(),
            'jobs'       => MyModel::findOrFail($id),
            'categories' => JobsCategories::get(),
            'cities'     => JobCities::get(),
        ];

        return view('user.job.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['title' => 'required']);

        $jobs = MyModel::findOrFail($id);
        $postData = $request->all();

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
            } else {
                unset($postData['img']);
            }
        }

        $jobs->update($postData);

        return redirect('job/myjob')->with('flash_message', 'Job updated!');
    }
    public function show($id_job)
    {

        $id = \Auth::id();
        $result['userId'] = $id;
        $result['isAdmin'] = $this->isAdmin();
        $result['user_data'] = $this->userData();
        $result['jobs'] = MyModel::findOrFail($id_job);
        return view('user.job.show', $result);
    }
    /*   public function getJobsByUserId(Request $request)
    {
    $filters              = json_decode($request->get('filters'));
    $result               = [];
    $result['jobs']       = MyModel::where('user_id','=', $filters->user_id)->paginate(5);
    $result['city']       = JobCities::get();
    $result['categories'] = JobsCategories::get();

    return $result;
    }*/

    public function getJob(Request $request)
    {
        $id = json_decode($request->get('id'));
        $result = [];
        $result['job'] = MyModel::where('id', '=', $id)->get();
        $result['city'] = JobCities::get();
        $result['categories'] = JobsCategories::get();
        return $result;
    }

    /*  public function deleteJobsByUserId(Request $request)
    {
    $id  = $request->get('id');
    return MyModel::where('id','=', $id)->delete();
    }*/
    public function deleteJobsByUserId($id)
    {
        $userId = \Auth::id();
        $myevent=MyModel::select('user_id')->where('id',$id)->first();
        if($userId == $myevent['user_id']){
         MyModel::destroy($id);
        }
        return redirect()->back();
    }
    public function updateJobAccount(Request $request)
    {
        $postData = $request->all();
        $validator = \Validator::make($postData, [
            'title' => 'required',
            'description' => 'required',
            'number' => 'required',
            'email' => 'required|email',
            'cat_id' => 'required',
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors(),
            ];
        }
        if (isset($postData['img'])) {
            $img = $this->fileUpload($request);
            $postData['img'] = $img;
        }

        $id = $postData['id'];
        unset($postData['_token']);
        unset($postData['id']);
        $postData['user_id'] = \Auth::id();
        $jobs = MyModel::where('id', '=', $id)->update($postData);

        return [
            'status' => true,
        ];
    }
    public function mygetjob($id)
    {
        $jobs = MyModel::where('cat_id', $id)->get();
        return view('job_more', compact('jobs'));
    }

}
