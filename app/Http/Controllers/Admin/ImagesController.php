<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Images;
use Intervention\Image\ImageManagerStatic as Image;
use App\Enum\{DBConsts, ImgPath};
class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdmin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Images::get();
        return view('admin/images/index',compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         $images = Images::get();

        return view('admin.images.create', compact('images'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
           $this->validate($request, ['title' => 'required']);

        $postData            = $request->all();
        $postData['images']  = $this->filesUpload($request);
        $postData['title'] = $request->title;
        Images::create($postData);
        return redirect('admin/images')->with('flash_message', 'Images added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($id)
    {
        $images = Images::findOrFail($id);

        return view('admin.images.show', compact('images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $images = Images::findOrFail($id);
        return view('admin.images.edit', compact('images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
        {
    $this->validate($request, ['title' => 'required']);

    $images1  = Images::findOrFail($id);
    $postData   = $request->all();
    if (isset($postData['image_uploaded'])) {
        $image_uploaded = $postData['image_uploaded'];
    }

    if (!empty($postData['images'])) {
        $postData['images'] = $this->filesUpload($request);
        if (!empty($image_uploaded)) {
            $postData['images'] = json_encode(array_merge(json_decode($postData['images']), $image_uploaded));
        }
    } else {
        if (!empty($image_uploaded)) {
            $postData['images'] = json_encode($image_uploaded);
        }
        else {
            unset($postData['images']);
        }
    }

    $images = json_decode($images1->images);
    $deleteImg = array_diff($images, json_decode($postData['images']));
    sort($deleteImg);

    if (isset($postData['image_uploaded'])) {
        for ($i=0; $i < count($deleteImg); $i++) {
            if ($deleteImg[$i] != null) {

                unlink(public_path("images/Images/").$deleteImg[$i]);
            }
        }
    }
    unset($postData['image_uploaded']);
   $images1->update($postData);
    return redirect('admin/images')->with('flash_message', 'Images updated!');
   
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
      public function destroy($id)
    {
        $images = Images::findOrFail($id);
        if(file_exists(public_path("images/Images/").$images['images'])){
            unlink(public_path("images/Images/").$images['images']);
        }
        foreach (json_decode($images->images) as $value) {
           if(file_exists(public_path("images/Images/").$value)){
                unlink(public_path("images/Images/").$value);
            }
        }
        Images::destroy($id);    
        return redirect('admin/images')->with('flash_message', 'Images deleted!');
    }





 public function filesUpload(Request $request){
        $this->validate($request, ['images' => 'required']);
        $this->validate($request, [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $photos = $request->file('images');
        $arr  = [];

        $indexLoop = 0;
        
        foreach ($photos as $pic) {
           
            $imagename       = uniqid(time()) . '.' . $pic->getClientOriginalExtension();
            $destinationPath = public_path("images/Images/");
            
            $img = Image::make($pic);
            $img->save($destinationPath.$imagename);
           
            $arr[] = $imagename;
            $indexLoop++;
        }
        return json_encode($arr);

    }
}
