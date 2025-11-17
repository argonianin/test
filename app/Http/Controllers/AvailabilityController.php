<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

/**
 * Класс для работы со слотами
 */
class AvailabilityController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Метод отдаёт перечень доступных слотов
     * @return JsonResponse
     */
    public function availability(): JsonResponse
    {
        $data = [];

        return response()->json($data, 200);
    }

    /**
     * Метод создаёт холд, проверяя предварительно доступность мест.
     * Проверяем доступность на запись (есть ли блокировка), если есть, дожидаемся доступность, если нет, блокируем таблицу, получаем данные и при доступности, создаем холд, уменьшая счётчик.
     * При отсутствии мест возвращает 409 Conflict
     * @param int $id
     * @return JsonResponse
     */
    public function hold(int $id): JsonResponse
    {
        $data = ["status" => ""];


        return response()->json($data, 200);
    }

}
