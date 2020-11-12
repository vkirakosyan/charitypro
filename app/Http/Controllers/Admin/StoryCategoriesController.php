<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\StoryCategories;
use App\Enum\DBConsts;
use Illuminate\Http\Request;

class StoryCategoriesController extends Controller implements DBConsts
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
        $story_category = !empty($keyword) ? StoryCategories::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : StoryCategories::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.story_categories.index', compact('story_category', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.story_categories.create');
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
        $this->validate($request, ['name' => 'required|unique:story_categories']);

        $postData = $request->all();
        
        StoryCategories::create($postData);

        return redirect('admin/story_categories')->with('flash_message', 'Story category added!');
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
        $story_categories = StoryCategories::findOrFail($id);

        return view('admin.story_categories.show', compact('story_categories'));
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
        $story_categories = StoryCategories::findOrFail($id);

        return view('admin.story_categories.edit', compact('story_categories'));
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

        $storyCategories = StoryCategories::findOrFail($id);
        $postData            = $request->all();

        $storyCategories->update($postData);

        return redirect('admin/story_categories')->with('flash_message', 'Story category updated!');
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
        StoryCategories::destroy($id);

        return redirect('admin/story_categories')->with('flash_message', 'Story category deleted!');
    }

}
