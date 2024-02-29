<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\AbstractRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Closure;

abstract class AbstractRepository implements AbstractRepositoryContract
{
    protected Model $model;

    protected LoggerInterface $log;

    public function __construct(Model $model, LoggerInterface $log)
    {
        $this->model = $model;
        $this->log = $log;
    }

    public function findOrFail($id, array $with = []): Model
    {
        return $this->model->with($with)->findOrFail($id);
    }

    public function findAll(array $where = [], array $with = [], array $sort = []): Collection
    {
        $this->validateWith($with);
        $result = $this->model->with($with);

        $this->buildWhereClauses($where, $result);
        $this->buildSort($sort, $result);

        return $result->get();
    }

    public function findAllPaged(
        array $where = [],
        array $with = [],
        array $sort = [],
        int $page = 1,
        int $limit = 15
    ): LengthAwarePaginatorContract {
        $this->validateWith($with);
        $result = $this->model->with($with);

        $this->buildWhereClauses($where, $result);
        $this->buildSort($sort, $result);

        $paginator = $result->paginate($limit, ['*'], 'page', $page);
        $paginator->appends(request()->query());
        return $paginator;
    }

    public function create(array $data = []): Model
    {
        $model = $this->model->newInstance($data);
        $model->save();

        $this->log->info('Created model', [
            'model_id' => $model->id,
            'model' => get_class($model),
        ]);

        return $model;
    }

    public function update(Model $model, array $data): Model
    {
        if (!$model->update($data)) {
            throw new \Exception(sprintf('%s[%d] failed to update.', get_class($model), $model->id));
        }

        $this->log->info('Updated model', [
            'model_id' => $model->id,
            'model' => get_class($model),
        ]);

        return $model;
    }

    public function delete(Model $model): bool
    {
        if (!$model->delete()) {
            throw new \Exception(sprintf('%s[%d] failed to delete.', get_class($model), $model->id));
        }

        $this->log->info('Deleted model', [
            'model_id' => $model->id,
            'model' => get_class($model),
        ]);

        return true;
    }

    public function updateOrCreate(array $attributes, array $values = []): Model
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    protected function buildWhereClauses(array $where, Builder &$query): void
    {
        foreach ($where as [$field]) {
            if ($field === 'deleted_at') {
                $query->withTrashed();
                break;
            }
        }

        $whereClosures = array_filter($where, fn (array $clause) => $clause[0] instanceof \Closure);
        $where = array_filter($where, fn (array $clause) => !($clause[0] instanceof \Closure));

        $whereIns = array_filter($where, fn (array $clause) => $clause[1] === 'in');
        $whereNotIns = array_filter($where, fn (array $clause) => $clause[1] === 'not in');

        array_map(fn (array $clause) => $query->whereIn($clause[0], $clause[2]), $whereIns);
        array_map(fn (array $clause) => $query->whereNotIn($clause[0], $clause[2]), $whereNotIns);

        $wheres = array_filter($where, fn (array $clause) => $clause[1] !== 'in' && $clause[1] !== 'not in');
        $query->where(array_merge($wheres, $whereClosures));
    }

    protected function buildSort(array $sort, Builder &$query): void
    {
        foreach ($sort as $key => $direction) {
            $query->orderBy($key, $direction);
        }
    }

    protected function validateWith(array $with): void
    {
        foreach ($with as $relationship) {
            if (!$this->model->isRelation($relationship)) {
                throw new ModelNotFoundException(sprintf(
                    'Relationship `%s` does not exist on model %s',
                    $relationship,
                    get_class($this->model)
                ));
            }
        }
    }
}
