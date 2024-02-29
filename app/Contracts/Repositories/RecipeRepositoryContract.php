<?php
declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RecipeRepositoryContract extends AbstractRepositoryContract
{
    public function search(array $parameters, int $page = 1, int $limit = 15): LengthAwarePaginator;
}
