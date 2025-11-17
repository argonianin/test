<?php


namespace App\Repositories;


use App\Http\Resources\HoldsResource;
use App\Models\Hold;
use App\Models\Slot;
use App\Repositories\Interfaces\HoldsRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class HoldsRepository
 *
 * @package App\Repositories\Holds
 */
class HoldsRepository extends BaseRepository implements HoldsRepositoryInterface
{
    /**
     * HoldsRepository constructor.
     *
     * @param Hold $hold
     */
    public function __construct(Hold $hold)
    {
        $this->model = $hold;
    }

    /**
     * Метод создания холда. Проверяем доступность мест* и возвращаем исключение в случае отсутствия или ид холда в случае успеха.
     *
     * @param integer $id
     *
     * @return int
     */
    public function setHold(int $id): int
    {

        $hold_id = null;

        // Проверяем наличие доступных мест.
        // Поскольку это только холд, но еще не бронирование, даже если кто-то в момент после получения данных забронирует место,
        // мы в любом случае при бронировании потом, будем проверять доступность, и там уже будем следить за этим строго. Подробнее в видео.
        // Здесь сознательно не реализован кеш,
        $slot_status = $this->getSlotAvailability($id);

        if ($slot_status==Slot::HAS_PLACES) {

            // Начинаем транзакцию
            DB::beginTransaction();

            try {

                // Создаем запись в таблице холдов со статусом held
                $hold_id = DB::table('holds')->insertGetId(['slot_id' => $id, 'status' => $this->model::STATUS_HELD, 'created_at' => now()]);

                DB::commit();
            } catch (\Exception $e) {

                // отменяем транзакцию, если что-то пошло не так
                DB::rollBack();
                report($e);
                throw new \Exception($e);
            }

        } elseif ($slot_status==Slot::NO_PLACES)  {

            throw new \Exception('No slots remaining');
        } elseif ($slot_status==Slot::SLOT_NOT_FOUND)  {

            throw new \Exception('Slot not found');
        } else {

            throw new \Exception('Unexpected error');
        }

        return $hold_id;
    }

    /**
     * Метод подтверждения холда.
     *
     * @param int $id
     *
     * @return string
     */
    public function setConfirm(int $id): ?int
    {
        $status = null;

        return $status;
    }

    /**
     * Метод удаления холда.
     *
     * @param int $id
     *
     * @return string
     */
    public function deleteHold(int $id): ?int
    {

        $status = null;

        return $status;
    }

    /**
     * Метод проверки доступности мест у конкретного слота по его ид.
     *
     * @param integer $id
     *
     * @return int
     */
    public function getSlotAvailability(int $id): ?int
    {

        $slot = Cache::get('slots:by_id:'.$id);

        if (empty($slot)) {

            // Начинаем транзакцию. Блокируем данную запись, чтобы избежать одновременных обновлений кеша
            $lock = Cache::lock('slots-by_id-'.$id.'-lock', 5);

            try {

                // Если запись была заблокирована предыдущим клиентом, ждем пока она освободится
                $lock->block(2);

                // Проверяем, не появился ли кеш, если да, отдаем кешированный результат
                if (Cache::has('slots:by_id:'.$id)) {
                    $slot = Cache::get('slots:by_id:'.$id);
                }

                // Делаем запрос к бд и записываем результат в кеш
                $slot = Slot::where('id', $id)->get()->first();
                Cache::put('slots:by_id:'.$id, $slot);

            } catch (LockTimeoutException $e) {

                // Если по какой-то причине, за время блокировки (5 сек), запись не освободилась, отдаем ответ клиенту 409 Conflict и освобождаем ресурсы
                report($e);
                throw new \Exception('Lock timeout');

            } finally {

                // Освобождаем блокировку после успешного обновления кеша
                $lock->release();
            }
        }

        if (!empty($slot)) {

            if ($slot->remaining > 0) {

                return Slot::HAS_PLACES;
            } else {

                return Slot::NO_PLACES;
            }
        } else {

            return Slot::SLOT_NOT_FOUND;
        }

    }

}
