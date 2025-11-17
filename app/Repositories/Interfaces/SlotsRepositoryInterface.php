<?php


namespace App\Repositories\Interfaces;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection;

/**
 * Interface SlotsRepositoryInterface
 *
 * @package App\Repositories\Slots
 */
interface SlotsRepositoryInterface extends BaseRepositoryInterface
{

    /**
     * @return AnonymousResourceCollection
     */
    public function getAvailability(): ?AnonymousResourceCollection;

}
