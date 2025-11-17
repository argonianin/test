<?php


namespace App\Repositories;


use App\Http\Resources\SlotsResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;
use App\Models\Slot;
use App\Repositories\Interfaces\SlotsRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class SlotsRepository
 *
 * @package App\Repositories\Slots
 */
class SlotsRepository extends BaseRepository implements SlotsRepositoryInterface
{
    /**
     * SlotsRepository constructor.
     *
     * @param Slot $slot
     */
    public function __construct(Slot $slot)
    {
        $this->model = $slot;
    }

    /**
     * Метод получения списка всех доступных слотов с их параметрами (количество доступных и свободных мест)
     *
     * @return AnonymousResourceCollection
     */
    public function getAvailability(): ?AnonymousResourceCollection
    {

        // Пробуем получить данные из кеша
        $slots = Cache::get('slots:all');

        // Если данных нет, пробуем собрать кеш
        if (empty($slots)) {

            // Начинаем транзакцию. Блокируем данную запись, чтобы избежать одновременных обновлений кеша
            $lock = Cache::lock('slots-all-lock', 5);

            try {

                // Если запись была заблокирована предыдущим клиентом, ждем пока она освободится
                $lock->block(2);

                // Проверяем, не появился ли кеш, если да, отдаем кешированный результат
                if (Cache::has('slots:all')) {
                    return SlotsResource::collection(Cache::get('slots:all'));
                }

                // Делаем запрос к бд и записываем результат в кеш
                $slots = $this->model->query()->get();
                Cache::put('slots:all', $slots);

            } catch (LockTimeoutException $e) {

                // Если по какой-то причине, за время блокировки (5 сек), запись не освободилась, отдаем ответ клиенту 409 Conflict и освобождаем ресурсы
                report($e);
                throw new Exception('Lock timeout');

            } finally {

                // Освобождаем блокировку после успешного обновления кеша
                $lock->release();
            }

        }

        return SlotsResource::collection($slots);
    }

}
