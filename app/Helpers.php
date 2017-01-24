<?php namespace App;

class Helpers
{
    public static function convertDate($date, $formatOld = 'Y-m-d', $formatNew = 'd-m-Y')
    {
        $date = date_create_from_format($formatOld, $date);
        return date_format($date, $formatNew);
    }
}
