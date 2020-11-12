<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\JobsCategories;
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;

class JobsCategoriesController extends Controller implements DBConsts
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
        $keyword = $request->get('search');
        $job_categories = !empty($keyword) ? JobsCategories::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : JobsCategories::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.jobs_categories.index', compact('job_categories', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.jobs_categories.create');
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
        $this->validate($request, ['name' => 'required|unique:job_categories']);

        $postData = $request->all();
        $postData['img'] = $this->fileUpload($request);
        
        JobsCategories::create($postData);

        return redirect('admin/jobs_categories')->with('flash_message', 'Job category added!');
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
        $jobs_categories = JobsCategories::findOrFail($id);

        return view('admin.jobs_categories.show', compact('jobs_categories'));
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
        $jobs_categories = JobsCategories::findOrFail($id);

        return view('admin.jobs_categories.edit', compact('jobs_categories'));
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
        $this->validate($request, ['name' => 'required']);

        $jobsCategories = JobsCategories::findOrFail($id);
        $postData            = $request->all();

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

        $jobsCategories->update($postData);

        return redirect('admin/jobs_categories')->with('flash_message', 'Job category updated!');
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
        JobsCategories::destroy($id);

        return redirect('admin/jobs_categories')->with('flash_message', 'Job category deleted!');
    }

    public function fileUpload(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('img');

        $input['imagename'] = uniqid(time()) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path(ImgPath::JOBS);

        $image->move($destinationPath, $input['imagename']);

        return $input['imagename'];
    }
}
