<?php

namespace App\Enums;

enum Sports : string
{
    case IceHockey = 'Ice Hockey';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::IceHockey => 'Hockey',
        };
    }

    static public function getOptions() {
        return array_column(self::cases(), 'value', 'name');
    }

    static public function getDefault() {
        return self::IceHockey->name;
    }

    static public function getRandomName() {
        $item = rand(0,count(self::cases())-1);
        return self::cases()[$item]->name;
    }
}


?>