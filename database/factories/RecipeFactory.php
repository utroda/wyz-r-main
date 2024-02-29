<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RecipeFactory extends Factory
{
    private $units = [
        'bunch',
        'oz',
        'lbs',
        'pinch',
        'each',
    ];

    public function definition(): array
    {
        $name = fake()->words(rand(1, 3), true);
        $images = [];
        $k = 0;
        while ($k < rand(1, 5)) {
            $images[] = 'https://placekitten.com/' . rand(1, 5) * 100 . '/' . rand(1, 5) * 100;
            ++$k;
        }
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'author_email' => fake()->safeEmail(),
            'description' => fake()->sentences(rand(3, 15), true),
            'steps' => fake()->sentences(rand(5, 15)),
            'images' => $images,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Recipe $recipe) {
            $proteins = Ingredient::where('type', 'protein')->inRandomOrder()->limit(1)->pluck('id');
            foreach ($proteins as $p) {
                $recipe->ingredients()->attach($p, [
                    'qty' => rand(1,10),
                    'unit' => 'portion',
                ]);
            }
            $others = Ingredient::where('type', '!=', 'protein')->inRandomOrder()->limit(rand(1, 4))->pluck('id');
            foreach ($others as $o) {
                $recipe->ingredients()->attach($o, [
                    'qty' => rand(1,10),
                    'unit' => Arr::random($this->units),
                ]);
            }
        });
    }
}
