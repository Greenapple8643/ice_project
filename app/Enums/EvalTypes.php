<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum EvalTypes : string implements HasLabel
{
    case FullIceScrimmage = 'Full Scrimmage';
    case HalfIceScrimmage = 'Half Scrimmage';
    case Skills = 'Skills';

    public function getLabel(): ?string
    {
        return $this->name;
        return match ($this) {
            self::FullIceScrimmage => 'Full Ice Scrimmage',
            self::HalfIceScrimmage => 'Half Ice Scrimmage',
            self::Skills => 'Skills',
        };
    }

    static public function getOptions() {
        return array_column(self::cases(), 'value', 'name');
    }

    static public function getDefault() {
        return self::FullIceScrimmage->name;
    }

    static public function getRandomName() {
        $item = rand(0,count(self::cases())-1);
        return self::cases()[$item]->name;
    }
}


?>