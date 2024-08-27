<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $item = rand(1,3);
        switch ($item) {
            case 1 :
                return [
                    'abbr' => 'HL',
                    'name' => 'House League',
                ];
                break;
            case 2 :
                return [
                    'abbr' => 'Comp',
                    'name' => 'Competitive',
                ];
                break;
            case 3 :
                return [
                    'abbr' => 'REC',
                    'name' => 'Rec League',
                ];
                break;    
        }
    }
    
    public function houseleague(): Factory  {
        return $this->state(function (array $attributes) {
            return [
                'abbr' => 'HL',
                'name' => 'House League',
            ];
        });
    }

    public function competitive(): Factory  {
        return $this->state(function (array $attributes) {
            return [
                'abbr' => 'COMP',
                'name' => 'Competitive',
            ];
        });
    }
    
    public function recreational(): Factory  {
        return $this->state(function (array $attributes) {
            return [
                'abbr' => 'REC',
                'name' => 'Rec League',
            ];
        });
    }

}
