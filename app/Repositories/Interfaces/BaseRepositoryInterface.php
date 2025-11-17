<?php


namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repository\Base
 */
interface BaseRepositoryInterface
{
    /**
     * @return Builder
     */
    public function query(): Builder;

    /**
     * @return Collection
     */
    public function all(): Collection;

    /**
     * @param array $attributes
     */
    public function create(array $attributes);

    /**
     * @param array $attributes
     * @param int $id
     *
     * @return bool
     */
    public function update(array $attributes, int $id): bool;

    /**
     * @param $id
     */
    public function find($id);

    /**
     * @param int $id
     * @return mixed
     */
    public function restore(int $id);

    /**
     * @param int $id
     *
     * @return bool|null|string
     * @throws \Exception
     */
    public function destroy(int $id): ?bool;
}
