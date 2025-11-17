<?php


namespace App\Repositories\Interfaces;


use App\Models\Holds;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

/**
 * Interface HoldsRepositoryInterface
 *
 * @package App\Repositories\Holds
 */
interface HoldsRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @param Integer $id
     *
     * @return int
     */
    public function setHold(int $id): int;

    /**
     * @param  Integer  $id
     *
     * @return ?int
     */
    public function setConfirm(int $id): ?int;

    /**
     * @param  Integer  $id
     *
     * @return ?int
     */
    public function deleteHold(int $id): ?int;

}
