<?php
declare(strict_types=1);

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->text('description');
            $table->json('steps');
            $table->json('images')->nullable();
            $table->string('author_email');

            $table->timestamps();
        });

        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('generic');
            $table->text('description')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->timestamps();

        });

        Schema::create('ingredient_recipe', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('qty');
            $table->string('unit');

            $table->foreignIdFor(Recipe::class)->cascadeOnDelete();
            $table->foreignIdFor(Ingredient::class)->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_recipe');
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('ingredients');
    }
};
