<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ForumComments;
use App\Enum\DBConsts;
use Illuminate\Http\Request;

class ForumCommentsController extends Controller implements DBConsts
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
        $forum_comments = !empty($keyword) ? ForumComments::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : ForumComments::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.forum_comments.index', compact('forum_comments', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.forum_comments.create');
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
        $posts               = ForumComments::create($postData);
        return redirect('admin/forum_comments')->with('flash_message', 'Post added!');
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
        $forum_comments = ForumComments::findOrFail($id);

        return view('admin.forum_comments.show', compact('forum_comments'));
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
        $forum_comments = ForumComments::findOrFail($id);
        return view('admin.forum_comments.edit', compact('forum_comments'));
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
        $forumComments = ForumComments::findOrFail($id);
        $postData      = $request->all();

        $forumComments->update($postData);

        return redirect('admin/forum_comments')->with('flash_message', 'Post Comment updated!');
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
        ForumComments::destroy($id);

        return redirect('admin/forum_comments')->with('flash_message', 'Post comment deleted!');
    }
}
