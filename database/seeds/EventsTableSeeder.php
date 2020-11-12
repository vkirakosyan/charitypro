<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enum\{DBConsts, ImgPath};
use Intervention\Image\ImageManagerStatic as Image;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $events = DB::table('events')->select('*')->get();
        foreach ($events as $key => $event) {
        	$event_id = $event->id;
        	$orig_img = public_path(ImgPath::EVENTS.$event->img);
        	$image = file_get_contents($orig_img);
        	$ext = strstr($orig_img,'.');
        	$new_image = uniqid(time()) . $ext;
            Image::configure(array('driver' => 'imagick'));
        	$new_img = Image::make($image);
        	$destinationPath = public_path(ImgPath::EVENTS);
        	$width = $new_img->width();
            $height = $new_img->height();
            if($width == $height)
            {
                $new_img->resize(500, 500)->save($destinationPath.'/'.$new_image);
            }
            else if($width > $height)
            {   
                $divisor = $width / 500;
                $new_img->resize(500, floor($height / $divisor))->save($destinationPath.'/'.$new_image);
            }
            else
            {
                $divisor = $height / 500;
                $new_img->resize(floor($width / $divisor), 500)->save($destinationPath.'/'.$new_image);
            }
        	DB::table('events')->where('id', $event_id)->update(['img' => $new_image]);
        	unlink($orig_img);
        }
    }
}
