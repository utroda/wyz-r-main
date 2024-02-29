<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'steps',
        'author_email',
        'images',
    ];

    protected $casts = [
        'steps' => 'array',
        'images' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('qty', 'unit');
    }
}
