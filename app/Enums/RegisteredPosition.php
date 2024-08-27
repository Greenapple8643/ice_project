<?php

namespace App\Enums;

enum RegisteredPosition : string
{
    case Player = 'Player';
    case Goalie = 'Goalie';
    case PlayerGoalie = 'Player/Goalie';

    static public function getOptions() {
        return array_column(self::cases(), 'value', 'name');
    }

    static public function getDefault() {
        return self::Player->name;
    }

    static public function getRandomName() {
        $item = rand(0,count(self::cases())-1);
        return self::cases()[$item]->name;
    }

}


?>