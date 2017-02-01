<?php namespace App;

class Helpers
{
    public static function convertDate($date, $formatOld = 'Y-m-d', $formatNew = 'd-m-Y')
    {

        $date = date_create_from_format($formatOld, $date);
        if ($date) {
            return date_format($date, $formatNew);
        }
        return false;
    }

    public static function convertPrice($data)
    {
        $price = ($data / 100);
        return number_format($price, 2, ",", ".");
    }

    public static function convertFullname($firstName, $lastName, $insertion = null) {
        if ($insertion) {
            $fullName = $firstName . ' ' . $insertion . ' ' . $lastName;
        } else {
            $fullName = $firstName . ' ' . $lastName;
        }

        return $fullName;
    }
}
