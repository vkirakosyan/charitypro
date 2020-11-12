<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enum\DBSettings;

class Settings extends Model
{
     /**
   * The table associated with the model.
   *
   * @var string
   */
    public $timestamps = false;
    protected $table = 'settings';
    protected $fillable = [
        'name',
        'data'
    ];

    public static function getYoutubeLinks()
    {
        $data = self::select('data')
            ->where('name', '=', DBSettings::YOUTUBE_LIKS)
            ->first();

        return !is_null($data) ? json_decode($data->data) : [];
    }

    public static function getSuccessStory()
    {
      $story = self::select('data')
            ->where('name', '=', DBSettings::SUCCESS_STORY)
            ->first();

      return !is_null($story) ? $story->data : 0;
    }
}
