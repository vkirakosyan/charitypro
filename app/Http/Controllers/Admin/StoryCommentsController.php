<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\StoryComments;
use App\Enum\DBConsts;
use Illuminate\Http\Request;

class StoryCommentsController extends Controller implements DBConsts
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
        $story_comments = !empty($keyword) ? StoryComments::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : StoryComments::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.story_comments.index', compact('story_comments', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.story_comments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request) {
        
        $postData            = $request->all();
        $postData['user_id'] = \Auth::id();
        $posts               = StoryComments::create($postData);
        return redirect('admin/story_comments')->with('flash_message', 'Story Comment added!');
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
        $story_comments = StoryComments::findOrFail($id);

        return view('admin.story_comments.show', compact('story_comments'));
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
        $story_comments = StoryComments::findOrFail($id);

        return view('admin.story_comments.edit', compact('story_comments'));
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

        $storyComments = StoryComments::findOrFail($id);
        $postData            = $request->all();

        $storyComments->update($postData);

        return redirect('admin/story_comments')->with('flash_message', 'Story coment updated!');
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
        StoryComments::destroy($id);

        return redirect('admin/story_comments')->with('flash_message', 'Story coment deleted!');
    }
}
