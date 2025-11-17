<?php


namespace App\Repositories;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * @param array $attributes
     * @param int $id
     *
     * @return bool
     */
    public function update(array $attributes, int $id): bool
    {
        $model = $this->find($id);

        $result = $model->update($attributes);

        if ($result) {
            $class = get_class($this->model);
            event("eloquent.updated: {$class}", $model);
        }

        return $model->update($attributes);
    }

    /**
     * @param $id
     *
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        return $this->query()->withTrashed()->find($id)->restore();
    }

    /**
     * @param int $id
     *
     * @return bool|string|null
     * @throws \Exception
     */
    public function destroy(int $id): ?bool
    {
        try {
            return $this->find($id)->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
