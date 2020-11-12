<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\{Stories, StoryCategories, StoryComments};
use App\Enum\{DBConsts, ImgPath};
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Settings;
use App\Enum\DBSettings;

class StoriesController extends Controller implements DBConsts
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
        $story   = !empty($keyword) ? Stories::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : Stories::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);
        $categories = StoryCategories::get();

        return view('admin.stories.index', compact('story', 'categories', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $categories = StoryCategories::get();
        $story_comments     = StoryComments::get();

        return view('admin.stories.create', compact('categories', 'story_comments'));
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
        $postData['images']  = $this->fileUpload($request);
        $postData['user_id'] = \Auth::id();
        $stories                = Stories::create($postData);

        $story = Settings::getSuccessStory();
        $check = Settings::where('name', '=', DBSettings::SUCCESS_STORY)->first();
        $is_success_story = $request->has('is_success_story') ? 1 : 0;

        if($is_success_story && $story != $stories->id)
        {
            if(is_null($check))
            {
                Settings::insert([
                    'name' => DBSettings::SUCCESS_STORY,
                    'data' => $stories->id
                ]);
            }
            else
            {
                Settings::where('name', '=', DBSettings::SUCCESS_STORY)
                     ->update([
                        'data' => $stories->id
                ]);
            } 
        }

        return redirect('admin/stories')->with('flash_message', 'Job added!');
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
        $stories = Stories::findOrFail($id);

        return view('admin.stories.show', compact('stories'));
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
        $stories  = Stories::findOrFail($id);
        $categories = StoryCategories::get();
        $story_comments     = StoryComments::get();
        return view('admin.stories.edit', compact('stories', 'categories', 'story_comments'));
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

        $stories  = Stories::findOrFail($id);
        $postData   = $request->all();
        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }

      /*  if(isset($postData['youtube_id'])) {
            $this->validate($request, ['youtube_id' => 'size:11']);
        }*/

        $story = Settings::getSuccessStory();
        $check = Settings::where('name', '=', DBSettings::SUCCESS_STORY)->first();
        $is_success_story = $request->has('is_success_story') ? 1 : 0;

        if($is_success_story && $story != $id)
        {
            if(is_null($check))
            {
                Settings::insert([
                    'name' => DBSettings::SUCCESS_STORY,
                    'data' => $id
                ]);
            }
            else
            {
                Settings::where('name', '=', DBSettings::SUCCESS_STORY)
                     ->update([
                        'data' => $id
                ]);
            } 
        }

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
        $images = json_decode($stories->images);

        $deleteImg = array_diff($images, json_decode($postData['images']));
        sort($deleteImg);
        
        if (isset($postData['image_uploaded'])) {
            for ($i=0; $i < count($deleteImg); $i++) { 
                if ($deleteImg[$i] != null) {
                    
                unlink(public_path(ImgPath::STORIES . $deleteImg[$i]));
                }
            }
        }

        $stories->update($postData);

        return redirect('admin/stories')->with('flash_message', 'Story updated!');
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
        Stories::destroy($id);

        $story = Settings::getSuccessStory();

        if(!empty($story) && $story == $id)
        {
            Settings::where('name', '=', DBSettings::SUCCESS_STORY)
                     ->update([
                        'data' => 0
                ]);
        }

        return redirect('admin/stories')->with('flash_message', 'Job deleted!');
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
            $destinationPath = public_path(ImgPath::STORIES);
            Image::configure(array('driver' => 'imagick'));
            $img = Image::make($image);
            $width = $img->width();
            $height = $img->height();
            if($width == $height)
            {
                $img->resize(600, 600)->save($destinationPath.'/'.$input['imagename']);
            }
            else if($width > $height)
            {   
                $divisor = $width / 600;
                $img->resize(600, floor($height / $divisor))->save($destinationPath.'/'.$input['imagename']);
            }
            else
            {
                $divisor = $height / 600;
                $img->resize(floor($width / $divisor), 600)->save($destinationPath.'/'.$input['imagename']);
            }

            array_push($arr, $input['imagename']);
        }

        return json_encode($arr);
    }
}
