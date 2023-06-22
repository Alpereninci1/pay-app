<?php

namespace App\Helpers;

class CommonHelper
{
    /**
     * @param $name
     * @return array
     */
    public static function  nameSplit($name) : array
    {
        $names = explode(' ', $name);
        $lastName = array_pop($names);
        $firstName = implode(' ',$names);
        return ['firstName' => $firstName,'lastName' => $lastName];
    }
}
