<?php

use Illuminate\Database\Seeder;
use App\Enum\DBSettings;
use App\Settings;

class YoutubeVideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            '5g3D6HQcbFw',
            'ajdjf2mh4V0'
        ];

        Settings::insert([
            'name' => DBSettings::YOUTUBE_LIKS,
            'data' => json_encode($data)
        ]);
    }
}
