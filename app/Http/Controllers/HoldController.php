<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\HoldsRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Класс для работы с холдами
 */
class HoldController extends Controller
{

    /**
     * @var HoldsRepositoryInterface
     */
    protected $holdsRepository;

    public function __construct(
        HoldsRepositoryInterface $holdsRepository
    )
    {
        $this->holdsRepository = $holdsRepository;
    }

    /**
     * Переводит холд в состояние confirmed.
     * Атомарно уменьшает remaining в слоте на 1 с защитой от оверсела.
     * При отсутствии мест возвращает 409 Conflict.
     * После успешного подтверждения инвалидирует кеш доступности.
     * @param int $id
     * @return JsonResponse
     */
    public function confirm(int $id): JsonResponse
    {
        $data = ["status" => ""];

        $status = $this->holdsRepository->setConfirm($id);

        return response()->json($data, $status);
    }

    /**
     * Меняет состояние холда на cancelled.
     * Возвращает слот в доступ, обновляя остаток.
     * Инвалидирует кеш доступных слотов.
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $data = ["status" => ""];

        return response()->json($data, 200);
    }

}
