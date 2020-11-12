<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Settings;
use App\Enum\DBSettings;

class HomeVideosController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdmin');
    }

    public function index(Request $request)
    {
        $videos = Settings::getYoutubeLinks();

        return view('admin.home_videos.index', compact('videos'));
    }

    public function show($id)
    {
        $videos = Settings::getYoutubeLinks();
        $video  = empty($videos) ? '' : $videos[$id];

        return view('admin.home_videos.show', compact('video', 'id'));
    }

    public function create()
    {
        return view('admin.home_videos.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'youtube_id' => 'required|size:11'
        ]);

        $videos = Settings::getYoutubeLinks();
        $check  = Settings::where('name', '=', DBSettings::YOUTUBE_LIKS)->first();

        $videos[] = $request->get('youtube_id');

        if (is_null($check)) {
            Settings::insert([
            'name' => DBSettings::YOUTUBE_LIKS,
            'data' => json_encode($videos)
        ]);
        } else {
            Settings::where('name', '=', DBSettings::YOUTUBE_LIKS)
                ->update([
                    'data' => json_encode($videos)
            ]);
        }

        return redirect('admin/home_videos')->with('flash_message', 'Video updated!');
    }

    public function edit($id)
    {
        $videos = Settings::getYoutubeLinks();
        $video  = empty($videos) ? '' : $videos[$id];

        return view('admin.home_videos.edit', compact('video', 'id'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'youtube_id' => 'required|size:11'
        ]);

        $videos = Settings::getYoutubeLinks();

        $videos[$id] = $request->get('youtube_id');

        Settings::where('name', '=', DBSettings::YOUTUBE_LIKS)
            ->update([
                'data' => json_encode($videos)
        ]);

        return redirect('admin/home_videos')->with('flash_message', 'Video updated!');
    }

    public function destroy($id)
    {
        $videos = Settings::getYoutubeLinks();

        if(!empty($videos)){
            unset($videos[$id]);
            Settings::where('name', '=', DBSettings::YOUTUBE_LIKS)
                ->update([
                    'data' => json_encode(array_values($videos))
            ]);
        }

        return redirect('admin/home_videos')->with('flash_message', 'Video deleted!');
    }
}
