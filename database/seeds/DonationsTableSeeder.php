<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enum\{DBConsts, ImgPath};
use Intervention\Image\ImageManagerStatic as Image;

class DonationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $donations = DB::table('donations')->select('*')->get();
        foreach ($donations as $key => $donation) {
        	$donation_id = $donation->id;
        	$orig_imgs = json_decode($donation->images);
        	$new_images = [];
        	foreach($orig_imgs as $orig_img)
        	{
	        	$destinationPath = public_path(ImgPath::DONATIONS);
	        	$image = file_get_contents($destinationPath.'/'.$orig_img);
	        	$ext = strstr($orig_img,'.');
	        	$new_image = uniqid(time()) . $ext;
	            Image::configure(array('driver' => 'imagick'));
	        	$new_img = Image::make($image);
	        	$width = $new_img->width();
	            $height = $new_img->height();
	            if($width == $height)
	            {
	                $new_img->resize(450, 450)->save($destinationPath.'/'.$new_image);
	            }
	            else if($width > $height)
	            {   
	                $divisor = $width / 450;
	                $new_img->resize(450, floor($height / $divisor))->save($destinationPath.'/'.$new_image);
	            }
	            else
	            {
	                $divisor = $height / 450;
	                $new_img->resize(floor($width / $divisor), 450)->save($destinationPath.'/'.$new_image);
	            }
	        	$new_images[] = $new_image;
	        	unlink($destinationPath.'/'.$orig_img);
        	}
        	DB::table('donations')->where('id', $donation_id)->update(['images' => json_encode($new_images)]);
        }
    }
}
