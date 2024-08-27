<?php

namespace App\Enums;

enum CanVolunteer : string
{
    case Yes = 'Yes';
    case No = 'No';
    case Maybe = 'Unsure at this time';

    static public function getOptions() {
        return array_column(self::cases(), 'value', 'name');
    }

    static public function getDefault() {
        return self::Maybe->name;
    }

    static public function getRandomName() {
        $item = rand(0,count(self::cases())-1);
        return self::cases()[$item]->name;
    }
}


?>