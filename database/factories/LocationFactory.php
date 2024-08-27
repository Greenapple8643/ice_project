<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Location;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->name();
        $abbr = $this->abbr($name);
        if (Location::exists($abbr)) {
            $abbr = $abbr . "-" . rand(1,99);
        }
        return [
            // 'abbr' => Str::slug(implode(" ", fake()->words(3)), ''),
            'abbr' => $abbr,
            'name' => $name,
            'address' => fake()->streetAddress(),
            'map_link' => fake()->url(),
            'public' => fake()->boolean(),
        ];
    }

    public function name():string {
        $suffixes = ['Arena', 'Centennial Arena', 'Complex', 'Recreational Complex', 'Sportsplex', 'Centre', 'Sports Centre'];
        $suffix = $suffixes[array_rand($suffixes)];
        $type = rand(1,3);
        switch ($type) {
            case 1:
                // Last Name Only
                $name = fake()->lastName();
                break;
            case 2:
                // Firt and Last Name
                $name = fake()->firstname() . " " . fake()->lastName();
                break;
            default:
                // City
                $name = fake()->city();
                break;
        };
        return $name . " " . $suffix;   
    }

    public function abbr($words): string
    {
        $abbr='';
        if (is_string($words)) {
            $words = explode(' ', $words);
        }
        foreach ($words as $word){
            // print ($word . "\n");
            $abbr = $abbr . $word[0];
        }
        return Str::upper($abbr);
    }

}
