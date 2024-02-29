<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\Repositories\RecipeRepositoryContract;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RecipeController extends Controller
{
    public function __construct(
        protected RecipeRepositoryContract $recipeRepository,
    )
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $results = $this->recipeRepository->search(
            $request->query('search') ?? [],
            (int)$request->query('page', 1),
            (int)$request->query('limit', 5),
        );
        return RecipeResource::collection($results);
    }

    public function show(Request $request, Recipe $recipe): RecipeResource
    {
        return new RecipeResource($recipe);
    }

    public function store(Request $request): RecipeResource
    {
        $validated = $this->validate($request, [
            'data.name' => 'required|min:3',
            'data.description' => 'required|min:3',
            'data.ingredients' => 'array',
            'data.author_email' => 'email',
            'data.steps' => 'array',
            'data.ingredients.*.qty' => 'required|integer|min:1',
            'data.ingredients.*.name' => 'required',
            'data.ingredients.*.unit' => 'required',
        ]);

        $recipe = $this->recipeRepository->create($validated['data']);
        return new RecipeResource($recipe);
    }
}
