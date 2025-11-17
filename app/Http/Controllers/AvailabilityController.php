<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\HoldsRepositoryInterface;
use App\Repositories\Interfaces\SlotsRepositoryInterface;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

/**
 * Класс для работы со слотами
 */
class AvailabilityController extends Controller
{

    /**
     * @var SlotsRepositoryInterface
     */
    protected $slotsRepository;

    /**
     * @var HoldsRepositoryInterface
     */
    protected $holdsRepository;

    public function __construct(
        SlotsRepositoryInterface $slotsRepository,
        HoldsRepositoryInterface $holdsRepository
    )
    {
        $this->slotsRepository = $slotsRepository;
        $this->holdsRepository = $holdsRepository;
    }

    /**
     * Метод отдаёт перечень доступных слотов
     *
     * @return JsonResponse
     */
    public function availability(): JsonResponse
    {
        try {
            $data = $this->slotsRepository->getAvailability();
        } catch (\Exception $e) {
            return response()->json([], 409);
        }

        return response()->json($data, 200);
    }

    /**
     * Метод создаёт холд, проверяя предварительно доступность мест.
     * Проверяем доступность на запись (есть ли блокировка), если есть, дожидаемся доступность, если нет, блокируем таблицу, получаем данные и при доступности, создаем холд, уменьшая счётчик.
     * При отсутствии мест возвращает 409 Conflict
     * В случае успеха возвращаем id холда
     * @param int $id
     * @return JsonResponse
     */
    public function hold(int $id): JsonResponse
    {

        try {
            $hold_id = $this->holdsRepository->setHold($id);
        } catch (\Exception $e) {
            return response()->json([$e], 409);
        }

        return response()->json($hold_id, 200);
    }

}
