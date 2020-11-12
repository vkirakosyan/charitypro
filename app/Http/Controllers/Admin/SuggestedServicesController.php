<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SuggestedServices;
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;

class SuggestedServicesController extends Controller implements DBConsts
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
        $imgPath = ImgPath::SUGGESTED_SERVICES;

        if (!empty($keyword)) {
            $services = SuggestedServices::findByTitile($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC');
        } else {
            $services = SuggestedServices::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);
        }

        return view('admin.suggested_services.index', compact('services', 'keyword', 'imgPath'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.suggested_services.create');
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

        $postData        = $request->all();
        $postData['img'] = $this->fileUpload($request);
        $service         = SuggestedServices::create($postData);

        return redirect('admin/suggested_services')->with('flash_message', 'Service added!');
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
        $service = SuggestedServices::findOrFail($id);

        return view('admin.suggested_services.show', compact('service'));
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
        $service = SuggestedServices::findOrFail($id);

        return view('admin.suggested_services.edit', compact('service'));
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

        $service  = SuggestedServices::findOrFail($id);
        $postData = $request->all();

        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }
        $postData['is_blocked'] = $request->has('is_blocked') ? 1 : 0;

        if (!empty($postData['img'])) {
            $postData['img'] = $this->fileUpload($request);
            if (!empty($image_uploaded)) {
                unlink(public_path(ImgPath::SUGGESTED_SERVICES . $image_uploaded));
            }
        } else {
            if (!empty($image_uploaded)) {
                $postData['img'] = $image_uploaded;
            }
            else {
                unset($postData['img']);
            }
        }

        $service->update($postData);

        return redirect('admin/suggested_services')->with('flash_message', 'Service updated!');
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
        $img  = SuggestedServices::find($id)->img;
        $path = public_path(ImgPath::SUGGESTED_SERVICES . $img);

        \File::delete($path);
        SuggestedServices::destroy($id);

        return redirect('admin/suggested_services')->with('flash_message', 'Service deleted!');
    }

    public function fileUpload(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('img');

        $input['imagename'] = uniqid(time()) . '.' . $image->getClientOriginalExtension();

        $destinationPath = public_path(ImgPath::SUGGESTED_SERVICES);

        $image->move($destinationPath, $input['imagename']);

        return $input['imagename'];
    }
}
