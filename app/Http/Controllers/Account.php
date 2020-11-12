<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Enum\ImgPath;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

class Account extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;
    
    public function home()
    {
        $id            = \Auth::id();
        $scriptsStyles = [
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
        ];

        return \View::make('account', $scriptsStyles);
    }
    public function edit(){
        $id=\Auth::id();


         $id            = \Auth::id();
        $scriptsStyles = [
            'userId'    => $id ? $id : 0,
            'isAdmin'   => $this->isAdmin(),
            'user_data' => $this->userData(),
            'user' => User::with('roles')->select('id', 'name', 'email', 'img', 'gender')->findOrFail($id),
        ];


         // $user = User::with('roles')->select('id', 'name', 'email', 'img', 'gender')->findOrFail($id);

        return view('user.user1.edit', $scriptsStyles);
    }

public function updateUserData(Request $request)
    {
        $id=\Auth::id();
         if (\Auth::user()->email !== trim($request->get('email'))) {
             $this->validate($request, ['email' => 'required|email|unique:users',]);
        }
        $this->validate($request, ['name' => 'required',]);

            if(isset($request->password)){
        $this->validate($request, ['password'         => 'required|min:6',
                                   'confirm_password' => 'required|same:password']);}

                
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

        return redirect('account')->with('flash_message', 'User updated!');
    }
    public function update(Request $request)
    {
        $postData  = $request->all();
        dd($postData);
        $validArr       = [
            'name'             => 'required',
            'gender'           => 'required',
        ];

        if (\Auth::user()->email !== trim($request->get('email'))) {
            $validArr['email'] = 'required|email|unique:users';
        }

        $validator = \Validator::make($postData, $validArr);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        if(isset($postData['img'])){
            $img             = $this->fileUpload($request);
            $postData['img'] = $img;
        }else if($postData['deletedImg']){
            $postData['img'] = null;
        }

        unset($postData['_token']);
        unset($postData['deletedImg']);
        unset($postData['crop-width']);
        unset($postData['crop-height']);
        unset($postData['crop-x']);
        unset($postData['crop-y']);
        $user = User::where('id','=', \Auth::id())->update($postData);

        return [
            'status' => true,
            'user' => $postData
        ];
    }

    public function updatePassword(Request $request)
    {
        $validArr = [];
        $postData  = $request->all();
        
        if(isset($postData['password']) && isset($postData['password_confirm'])){
            $validArr['password']         = 'required|min:8';
            $validArr['password_confirm'] = 'required|same:password';
        }

        $validator = \Validator::make($postData, $validArr);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        
        unset($postData['_token']);
        unset($postData['confirm_password']);
        $postData['password'] = bcrypt($postData['password']);
        $user = User::where('id','=', \Auth::id())->update($postData);

        return [
            'status' => true,
            'user' => $postData
        ];
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
    private function fileUpload1(Request $request)
    {
        $postData = $request->all();
        $width    = $postData['crop-width'];
        $height   = $postData['crop-height'];
        $x        = $postData['crop-x'];
        $y        = $postData['crop-y'];
        $validator = \Validator::make($request->all(), [
            'img' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }
        
        $images          = Input::file('img');
        $imageName       = uniqid(time()) . '.' . $images->getClientOriginalExtension();
        $destinationPath = public_path(ImgPath::USERS);
        Image::configure(array('driver' => 'imagick'));
        $img = Image::make($images);
        $img->crop($width,$height,$x,$y)->save($destinationPath.'/'.$imageName);

        return $imageName;
    }
}
