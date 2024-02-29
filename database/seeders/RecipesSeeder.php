<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RecipesSeeder extends Seeder
{
    public function run(): void
    {
        $protein = [
            'Wild Alaskan Sockeye',
            'Wild Alaskan Coho',
            'Wild Alaskan Cod',
            'Wild Alaskan Rockfish',
            'Wild Alaska Pollock',
            'Wild Alaskan Lingcod',
            'Wild Alaskan Halibut',
            'Wild Alaskan Sablefish',
        ];
        $starch = [
            'wild rice',
            'brocolli',
            'scapes',
            'bok choy',
            'potato',
        ];
        $seasoning = [
            'honey',
            'cardamom',
            'tarragon',
            'mint',
            'parsley',
            'cilantro',
            'chili powder',
            'salt',
            'butter',
            'oil',
            'black pepper',
            'white pepper',
            'sesame oil',
            'maple syrup',
            'soy sauce',
        ];

        $emails = [];
        for ($k = 0; $k < 5; ++$k) {
            $emails[] = fake()->safeEmail;
        }

        foreach ($protein as $p) {
            $i = Ingredient::firstOrCreate(['name' => $p]);
            if ($i->wasRecentlyCreated) {
                $i->description = fake()->paragraph;
                $i->type = 'protein';
                $i->price = rand(1000, 2000);
                $i->save();
            }
        }
        foreach (['starch', 'seasoning'] as $t) {
            foreach ($$t as $a) {
                $i = Ingredient::firstOrCreate(['name' => $a]);
                if ($i->wasRecentlyCreated) {
                    $i->type = $t;
                    $i->save();
                }
            }
        }
        $k = 0;
        while ($k < 3000) {
            $recipe = Recipe::factory()->create(
                [
                    'author_email' => Arr::random($emails)
                ]
            );
            $recipe->save();
            ++$k;
        }
    }
}
