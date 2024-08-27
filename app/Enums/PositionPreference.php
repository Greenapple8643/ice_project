<?php

namespace App\Enums;

enum PositionPreference : string
{
    case Forward = 'Forward';
    case Defence = 'Defence';
    case NoPreference = 'No Preference';

    static public function getOptions() {
        return array_column(self::cases(), 'value', 'name');
    }

    static public function getDefault() {
        return self::NoPreference->name;
    }

    static public function getRandomName() {
        $item = rand(0,count(self::cases())-1);
        return self::cases()[$item]->name;
    }

}

?>