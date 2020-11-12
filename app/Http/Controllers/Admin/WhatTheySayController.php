<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\WhatTheySay;
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;

class WhatTheySayController extends Controller implements DBConsts
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
        $wtsau   = (!empty($keyword)) ? WhatTheySay::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : WhatTheySay::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.what_they_say.index', compact('wtsau', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.what_they_say.create');
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
        $this->validate($request, ['name' => 'required']);

        $postData        = $request->all();
        $postData['img'] = $this->fileUpload($request);
        $whattheysay         = WhatTheySay::create($postData);

        return redirect('admin/what_they_say')->with('flash_message', 'Feedback added!');
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
        $whattheysay = WhatTheySay::findOrFail($id);

        return view('admin.what_they_say.show', compact('whattheysay'));
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
        $whattheysay = WhatTheySay::findOrFail($id);

        return view('admin.what_they_say.edit', compact('whattheysay'));
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

        $whattheysay  = WhatTheySay::findOrFail($id);
        $postData = $request->all();

        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }
        $postData['is_blocked'] = $request->has('is_blocked') ? 1 : 0;

        if (!empty($postData['img'])) {
            $postData['img'] = $this->fileUpload($request);
            if (!empty($image_uploaded)) {
                unlink(public_path(ImgPath::WHATTHEYSAY . $image_uploaded));
            }
        } else {
            if (!empty($image_uploaded)) {
                $postData['img'] = $image_uploaded;
            }
            else {
                unset($postData['img']);
            }
        }

        $whattheysay->update($postData);

        return redirect('admin/what_they_say')->with('flash_message', 'Feedback updated!');
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
        WhatTheySay::destroy($id);

        return redirect('admin/what_they_say')->with('flash_message', 'Feedback deleted!');
    }

    public function fileUpload(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('img');

        $input['imagename'] = uniqid(time()) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path(ImgPath::WHATTHEYSAY);

        $image->move($destinationPath, $input['imagename']);

        return $input['imagename'];
    }
}
