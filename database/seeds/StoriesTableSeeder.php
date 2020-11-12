<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enum\{DBConsts, ImgPath};
use Intervention\Image\ImageManagerStatic as Image;

class storiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stories = DB::table('stories')->select('*')->get();
        foreach ($stories as $key => $story) {
        	$story_id = $story->id;
        	$orig_imgs = json_decode($story->images);
        	$new_images = [];
        	foreach($orig_imgs as $orig_img)
        	{
	        	$destinationPath = public_path(ImgPath::STORIES);
	        	$image = file_get_contents($destinationPath.'/'.$orig_img);
	        	$ext = strstr($orig_img,'.');
	        	$new_image = uniqid(time()) . $ext;
	            Image::configure(array('driver' => 'imagick'));
	        	$new_img = Image::make($image);
	        	$width = $new_img->width();
	            $height = $new_img->height();
	            if($width == $height)
	            {
	                $new_img->resize(600, 600)->save($destinationPath.'/'.$new_image);
	            }
	            else if($width > $height)
	            {   
	                $divisor = $width / 600;
	                $new_img->resize(600, floor($height / $divisor))->save($destinationPath.'/'.$new_image);
	            }
	            else
	            {
	                $divisor = $height / 600;
	                $new_img->resize(floor($width / $divisor), 600)->save($destinationPath.'/'.$new_image);
	            }
	        	$new_images[] = $new_image;
	        	unlink($destinationPath.'/'.$orig_img);
        	}
        	DB::table('stories')->where('id', $story_id)->update(['images' => json_encode($new_images)]);
        }
    }
}
