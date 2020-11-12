<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\{Forum, ForumComments};
use App\Enum\DBConsts;
use Illuminate\Http\Request;

class ForumController extends Controller implements DBConsts
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
        $post   = !empty($keyword) ? Forum::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : Forum::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.forum.index', compact('post', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $post_comments = ForumComments::get();

        return view('admin.forum.create', compact('forum_comments'));
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
        $postData['user_id'] = \Auth::id();
        $posts               = Forum::create($postData);
        return redirect('admin/forum')->with('flash_message', 'Post added!');
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
        $posts = Forum::findOrFail($id);

        return view('admin.forum.show', compact('posts'));
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
        $posts = Forum::findOrFail($id);
        $post_comments = ForumComments::get();
        return view('admin.forum.edit', compact('posts', 'post_comments'));
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

        $posts  = Forum::findOrFail($id);
        $postData = $request->all();

        $posts->update($postData);

        return redirect('admin/forum')->with('flash_message', 'Post updated!');
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
        Forum::destroy($id);

        return redirect('admin/forum')->with('flash_message', 'Post deleted!');
    }
}
