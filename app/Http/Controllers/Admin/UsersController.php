<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\{Role, User};
use App\Enum\{DBConsts, UserRole, ImgPath};
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

class UsersController extends Controller implements DBConsts
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
        $users   = !empty($keyword) ? User::findByNameAndEmail($keyword, self::ADMIN_PER_PAGE)->orderBy('id', 'DESC') : User::orderBy('id', 'DESC')->paginate(self::ADMIN_PER_PAGE);

        return view('admin.users.index', compact('users', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.users.create');
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
        $this->validate($request, [
            'name'     => 'required|min:2',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'gender'   => 'required'
        ]);

        $data = $request->except('password');

        $data['img']      = $this->fileUpload($request);
        $data['gender']   = $request->get('gender');
        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $user->assignRole(UserRole::SIMPLE_USER);

        return redirect('admin/users')->with('flash_message', 'User added!');
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
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
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
        $user = User::with('roles')->select('id', 'name', 'email', 'img', 'gender')->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int      $id
     *
     * @return void
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required']);

        $data = $request->except('password');

        if ($request->has('password') && strlen($request->get('password')) > 0) {
            $data['password'] = bcrypt($request->get('password'));
        }


        if (isset($data['image_uploaded'])) {
            $image_uploaded = $data['image_uploaded'];
        }

        if (!empty($data['img'])) {
            $data['img'] = $this->fileUpload($request);
            if (!empty($image_uploaded)) {
                unlink(public_path(ImgPath::USERS . $image_uploaded));
            }
        } else {
            if (!empty($image_uploaded)) {
                $data['img'] = $image_uploaded;
            }
            else {
                unset($data['img']);
            }
        }

        $user = User::findOrFail($id);

        $user->update($data);

        return redirect('admin/users')->with('flash_message', 'User updated!');
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
        User::destroy($id);

        return redirect('admin/users')->with('flash_message', 'User deleted!');
    }

    public function fileUpload(Request $request)
    {

        $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images          = Input::file('img');
        $input['imagename'] = uniqid(time()) . '.' . $images->getClientOriginalExtension();
        $img = Image::make($images);
        $destinationPath = public_path(ImgPath::USERS);
        $img->fit(100, 100)->save($destinationPath.'/'.$input['imagename']);

        return $input['imagename'];
    }
}
