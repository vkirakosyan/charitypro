<?php

namespace App\Enum;

class DateHelper
{
    const TPYE_SHORT = 'short';
    const TYPE_LONG = 'long';

    private static $index, $type, $lang;

    public static function get($index, $lang = 'am')
    {
        self::$index = $index;
        self::$lang  = $lang;

        return new self;
    }

    public function monthName()
    {
        $data = [
            'am' => [
                'short' => ['Հուն', 'Փետ', 'Մար', 'Ապր', 'Մայ', 'Հուն', 'Հուլ', 'Օգս', 'Սեպ', 'Հոկ', 'Նոյ', 'Դեկ'],
                'long'  => ['Հունվար', 'Փոտրվար', 'Մարտ', 'Ապրիլ', 'Մայիս', 'Հունիս', 'Հուլիս', 'Օգոստոս', 'Սեպտեմբեր', 'Հոկտեմբեր', 'Նոյեմբեր', 'Դեկտեմբեր']
            ],
        ];

        if (!array_key_exists(self::$lang, $data)) {
            return null;
        }

        $data  = $data[self::$lang];
        $index = self::$index > 0 && self::$index < 13 ? self::$index - 1 : 12;

        if (!array_key_exists($index, $data[self::TPYE_SHORT])) {
            return null;
        }

        $retData = new \stdClass();

        $retData->{self::TPYE_SHORT} = $data[self::TPYE_SHORT][$index];
        $retData->{self::TYPE_LONG}  = $data[self::TYPE_LONG][$index];

        return $retData;
    }

    public function weekDay()
    {
        $data = [
            'am' => [
                'short' => ['Կիր', 'Երկ', 'Երք', 'Չոր', 'Հինգ', 'Ուրբ', 'Շաբ'],
                'long'  => ['Կիրակի', 'Երկուշաբթի', 'Երեքշաբթի', 'Չորեքշաբթի', 'Հինգշաբթի', 'Ուրբաթ', 'Շաբաթ']
            ]
        ];

        if (!array_key_exists(self::$lang, $data)) {
            return null;
        }

        $data = $data[self::$lang];

        if (!array_key_exists(self::$index, $data[self::TPYE_SHORT])) {
            return null;
        }
        $retData = new \stdClass();

        $retData->{self::TPYE_SHORT} = $data[self::TPYE_SHORT][self::$index];
        $retData->{self::TYPE_LONG}  = $data[self::TYPE_LONG][self::$index];

        return $retData;
    }
}