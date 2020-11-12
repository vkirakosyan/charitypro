<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\{Donations, DonationsCategories};
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class DonationsController extends Controller implements DBConsts
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
        $donation   = !empty($keyword) ? Donations::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : Donations::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);
        $categories = DonationsCategories::get();

        return view('admin.donations.index', compact('donation', 'categories', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $categories = DonationsCategories::get();

        return view('admin.donations.create', compact('categories'));
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
        $this->validate($request, ['name' => 'required', 'images' => 'required']);
        $postData            = $request->all();
        $postData['images']  = $this->fileUpload($request);
        $postData['user_id'] = \Auth::id();
        $donations           = Donations::create($postData);

        return redirect('admin/donations')->with('flash_message', 'Donation added!');
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
        $donations = Donations::findOrFail($id);

        return view('admin.donations.show', compact('donations'));
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
        $donations  = Donations::findOrFail($id);
        $categories = DonationsCategories::get();

        return view('admin.donations.edit', compact('donations', 'categories'));
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

        $donations  = Donations::findOrFail($id);
        $postData   = $request->all();
        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }
        $postData['is_blocked'] = $request->has('is_blocked') ? 1 : 0;

        if (!empty($postData['images'])) {
            $postData['images'] = $this->fileUpload($request);
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
        $images = json_decode($donations->images);

        $deleteImg = array_diff($images, json_decode($postData['images']));
        sort($deleteImg);
        
        if (isset($postData['image_uploaded'])) {
            for ($i=0; $i < count($deleteImg); $i++) { 
                if ($deleteImg[$i] != null) {
                    
                unlink(public_path(ImgPath::DONATIONS . $deleteImg[$i]));
                }
            }
        }
        $donations->update($postData);

        return redirect('admin/donations')->with('flash_message', 'Donation updated!');
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
        Donations::destroy($id);

        return redirect('admin/donations')->with('flash_message', 'Donation deleted!');
    }

    public function fileUpload(Request $request)
    {

        $this->validate($request, [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = $request->file('images');
        $arr    = [];

        foreach ($images as $image) {
            $input['imagename'] = uniqid(time()) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path(ImgPath::DONATIONS);
            Image::configure(array('driver' => 'imagick'));
            $img = Image::make($image);
            $width = $img->width();
            $height = $img->height();
            if($width == $height)
            {
                $img->resize(450, 450)->save($destinationPath.'/'.$input['imagename']);
            }
            else if($width > $height)
            {   
                $divisor = $width / 450;
                $img->resize(450, floor($height / $divisor))->save($destinationPath.'/'.$input['imagename']);
            }
            else
            {
                $divisor = $height / 450;
                $img->resize(floor($width / $divisor), 450)->save($destinationPath.'/'.$input['imagename']);
            }

            array_push($arr, $input['imagename']);
        }

        return json_encode($arr);
    }
}
