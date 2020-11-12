<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{DonationsCategories, Donations as MyModel};
use App\Enum\ImgPath;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Input;

class Donations extends Controller
{
    use \App\Enum\Traits\CheckAdminUser;
    use \App\Enum\Traits\UserData;
    

public function getDonCat(){
    $categories = DonationsCategories::get();
    $donate_categories = $categories->pluck('name','id');
    foreach($donate_categories as $key => $category){
    echo "<option value='$key'>$category</option>";
    }

}


    public function home($catId = 0)
    {

        $data          = MyModel::getNotBlocked($catId);
        $id            = \Auth::id();
        $scriptsStyles = [
            'styles' => [
/*                '/css/donations.css',*/
                '/css/swiper.min.css'
            ],
            'scripts' => [
                '/js/donations.js',
                '/js/swiper.min.js'
            ],
            'donateId' => 'donates',
            'userId'     => $id ? $id : 0,
            'isAdmin'    => $this->isAdmin(),
            'user_data'  => $this->userData(),
            'pageTitle'  => 'CharityPro - Նվիրաբերություն',
            'categories' => DonationsCategories::get(),
            'donations'  => $data,
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total'        => $data->total(),
                'per_page'     => $data->perPage()
            ]
        ];
        return \View::make('donations', $scriptsStyles);
    }

    public function getDonationsByUserId(Request $request)
    {
        $filters              = json_decode($request->get('filters'));
        $result               = [];
        $result['donations']  = MyModel::where('user_id','=', $filters->user_id)->paginate(5); 
        $result['categories'] = DonationsCategories::get();
        return $result;
    }
     public function getDonation(){
        $page=1;
        $id=\Auth::id();
        $result['userId'] = $id;
        $result['isAdmin']   = $this->isAdmin();
        $result['user_data'] = $this->userData();
        $result['donation']   = MyModel::where('user_id','=', $id)->latest()->get();
   
        $result['donation'] = json_decode(json_encode($result['donation']), false);
           

        foreach ($result['donation'] as  $value) {
            $value= json_decode(json_encode($value), true);
            array_push($result['donation'],$value);
            array_shift($result['donation']);
        }
        $result['categories'] = DonationsCategories::get();
        $count=count(array_chunk($result['donation'] ,6));
           if(isset($_GET['page']) && $_GET['page'] >=1) {
            $page=$_GET['page'];
         }
         if($count>1){
         $result['donation']=array_chunk($result['donation'],6)[$page-1];
     }
         $result['count']=$count;
        return \View::make('user.donation.index', $result);
    }
     public function edit($id)
    {
        $userId = \Auth::id();
        $data   = [
            'userId'     => $userId ? $userId : 0,
            'isAdmin'    => $this->isAdmin(),
            'user_data'  => $this->userData(),
            'donations'  => MyModel::findOrFail($id),
            'categories' => DonationsCategories::get(),
        ];

        return view('user.donation.edit', $data);
    }



      public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required']);

        $donations  = MyModel::findOrFail($id);
        $postData   = $request->all();
        if (isset($postData['image_uploaded'])) {
            $image_uploaded = $postData['image_uploaded'];
        }
        $postData['is_blocked'] = $request->has('is_blocked') ? 1 : 0;

        if (!empty($postData['images'])) {
            $postData['images'] = $this->fileUpload($request);
            if (!empty($image_uploaded)) {
             $postData['images']=json_encode($postData['images']);
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
        if(is_array($postData['images'])){
            $postData['images']=json_encode($postData['images']);
        }
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

        return redirect('donation/donation')->with('flash_message', 'Donation updated!');
    }
      public function show($id)
    {

        $result['userId'] = $id;
        $result['isAdmin']   = $this->isAdmin();
        $result['user_data'] = $this->userData();
        $result['donations'] =  MyModel::findOrFail($id);
        return view('user.donation.show', $result);
    }


  /*  public function deleteDonation(Request $request)
    {
        $id  = $request->get('id');
        return MyModel::where('id','=', $id)->delete();
    }*/
     public function deleteDonation($id)
    {
        $userId = \Auth::id();
        $myevent=MyModel::select('user_id')->where('id',$id)->first();
        if($userId == $myevent['user_id']){
         MyModel::destroy($id);
        }
         return redirect()->back();
    }

    public function create(Request $request)
    {
        // dd($request->all());
        $validator = \Validator::make($request->all(), [
            'name'        => 'required',
            'description' => 'required',
            'cat_id'      => 'required',
            'images'      => 'required',
            'phone'       => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back();
         /*   return [
                'errors' => $validator->errors()
            ];*/
        }

        $postData = $request->all();
        $images   = $this->fileUpload($request);
        unset($postData['_token']);

        $postData['images']  = json_encode($images);
        $postData['user_id'] = \Auth::id();
        $donations           = MyModel::create($postData);
        return redirect()->back();
    }

    private function fileUpload(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }
        $images = Input::file('images');
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

        return $arr;
    }

    public function updateDonationAccount(Request $request)
    {
        $postData      = $request->all();
        $validator = \Validator::make($postData, [
            'name'        => 'required',
            'description' => 'required',
            'phone'       => 'required',
            'cat_id'      => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors()
            ];
        }

        $issetImages = 0;

        if (isset($postData['images']) && count($postData['images']) != 0){
            $postData['images'] = $this->fileUpload($request);
            $issetImages = 1;
        } elseif (isset($postData['uploaded_images'])) {
            $postData['images'] = $postData['uploaded_images'];
        } else {
            $validator = \Validator::make($postData, ['images' => 'required']);
            return [
                'errors' => $validator->errors()
            ];
        }

        if (isset($postData['uploaded_images']) && $issetImages) {
            $images_merge       = array_merge($postData['images'], $postData['uploaded_images']);
            $postData['images'] = $images_merge;
        }

        $postData['images'] = json_encode($postData['images']);

        unset($postData['_token'], $postData['id'], $postData['uploaded_images']);
        MyModel::where('id','=', $postData['id'])->update($postData);

        return [
            'status' => true
        ];
    }
}