<?php
declare(strict_types=1);

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface AbstractRepositoryContract
{
    public function findOrFail($id, array $with = []): Model;

    public function findAll(array $where = [], array $with = [], array $sort = []): Collection;

    public function findAllPaged(
        array $where = [],
        array $with = [],
        array $sort = [],
        int $page = 1,
        int $limit = 15
    ): LengthAwarePaginator;

    public function create(array $data = []): Model;

    public function update(Model $model, array $data): Model;

    public function delete(Model $model): bool;

    public function updateOrCreate(array $attributes, array $values = []): Model;
}
