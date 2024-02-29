<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    public function toArray($request)
    {
        $ingredients = [];
        foreach ($this->resource->ingredients as $i) {
            $ingredients[] = [
                'id' => $i->id,
                'type' => $i->type,
                'name' => $i->name,
                'description' => $i->description,
                'price' => $i->price,
                'qty' => $i->pivot->qty,
                'unit' => $i->pivot->unit,
            ];
        }

        return [
            'id' => $this->resource->id,
            'slug' => $this->resource->slug,
            'name' => $this->resource->name,
            'author_email' => $this->resource->author_email,
            'description' => $this->resource->description,
            'ingredients' => $ingredients,
            'steps' => $this->resource->steps,
            'images' => $this->resource->images,
        ];
    }
}
