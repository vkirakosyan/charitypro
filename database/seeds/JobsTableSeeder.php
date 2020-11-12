<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enum\{DBConsts, ImgPath};
use Intervention\Image\ImageManagerStatic as Image;

class JobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobs = DB::table('jobs')->select('*')->get();
        foreach ($jobs as $key => $job) {
        	$job_id = $job->id;
        	$orig_img = public_path(ImgPath::JOBS.$job->img);
        	$image = file_get_contents($orig_img);
        	$ext = strstr($orig_img,'.');
        	$new_image = uniqid(time()) . $ext;
            Image::configure(array('driver' => 'imagick'));
        	$new_img = Image::make($image);
        	$destinationPath = public_path(ImgPath::JOBS);
        	$width = $new_img->width();
            $height = $new_img->height();
            if($width == $height)
            {
                $new_img->resize(300, 300)->save($destinationPath.'/'.$new_image);
            }
            else if($width > $height)
            {   
                $divisor = $width / 300;
                $new_img->resize(300, floor($height / $divisor))->save($destinationPath.'/'.$new_image);
            }
            else
            {
                $divisor = $height / 300;
                $new_img->resize(floor($width / $divisor), 300)->save($destinationPath.'/'.$new_image);
            }
        	DB::table('jobs')->where('id', $job_id)->update(['img' => $new_image]);
        	unlink($orig_img);
        }
    }
}
