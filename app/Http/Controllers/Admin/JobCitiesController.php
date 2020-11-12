<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\JobCities;
use App\Enum\DBConsts;
use Illuminate\Http\Request;

class JobCitiesController extends Controller implements DBConsts
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
        $job_cities = !empty($keyword) ? JobCities::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : JobCities::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.job_cities.index', compact('job_cities', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.job_cities.create');
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
        $this->validate($request, ['name' => 'required|unique:cities']);

        $postData = $request->all();
        
        JobCities::create($postData);

        return redirect('admin/job_cities')->with('flash_message', 'City added!');
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
        $job_cities = JobCities::findOrFail($id);

        return view('admin.job_cities.show', compact('job_cities'));
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
        $job_cities = JobCities::findOrFail($id);

        return view('admin.job_cities.edit', compact('job_cities'));
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

        $jobCities = JobCities::findOrFail($id);
        $postData  = $request->all();

        $jobCities->update($postData);

        return redirect('admin/job_cities')->with('flash_message', 'City updated!');
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
        JobCities::destroy($id);

        return redirect('admin/job_cities')->with('flash_message', 'City deleted!');
    }
}