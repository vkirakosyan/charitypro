<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DonationsCategories;
use App\Enum\DBConsts;
use Illuminate\Http\Request;

class DonationsCategoriesController extends Controller implements DBConsts
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
        $donation_categories = !empty($keyword) ? DonationsCategories::findByName($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : DonationsCategories::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.donations_categories.index', compact('donation_categories', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.donations_categories.create');
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
        $this->validate($request, ['name' => 'required|unique:donations_categories']);

        $postData = $request->all();

        DonationsCategories::create($postData);

        return redirect('admin/donations_categories')->with('flash_message', 'Donation category added!');
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
        $donations_categories = DonationsCategories::findOrFail($id);

        return view('admin.donations_categories.show', compact('donations_categories'));
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
        $donations_categories = DonationsCategories::findOrFail($id);

        return view('admin.donations_categories.edit', compact('donations_categories'));
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

        $donationsCategories = DonationsCategories::findOrFail($id);
        $postData            = $request->all();

        $donationsCategories->update($postData);

        return redirect('admin/donations_categories')->with('flash_message', 'Donation category updated!');
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
        DonationsCategories::destroy($id);

        return redirect('admin/donations_categories')->with('flash_message', 'Donation category deleted!');
    }
}
