<?php

declare(strict_types=1);


namespace Src\Services\OrderFeedback;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Models\Client\Client;
use Src\Models\Driver\Driver;
use Src\Models\SystemUsers\SystemWorker;
use Src\Repositories\OrderFeedback\OrderFeedbackContract;
use Src\Repositories\OrderFeedbackOption\OrderFeedbackOptionContract;

/**
 * Class OrderFeedbackService
 * @package Src\Services\OrderFeedback
 */
class OrderFeedbackService extends BaseService implements OrderFeedbackServiceContract
{
    /**
     * @var OrderFeedbackContract
     */
    protected OrderFeedbackContract $feedbackContract;

    /**
     * @var OrderFeedbackOptionContract
     */
    protected OrderFeedbackOptionContract $feedbackOptionContract;

    /**
     * @var string[]
     */
    protected array $types = [
        [
            'value' => 'positive',
            'text' => 'Позитивный отзыв',
            'color' => '#00C853'
        ],
        [
            'value' => 'negative',
            'text' => 'Негавтивный отзыв',
            'color' => '#C62828'
        ],
        [
            'value' => 'none',
            'text' => 'Без оценки',
            'color' => '#BDBDBD'
        ]
    ];

    /**
     * @var string[]
     */
    protected array $writers = [
        [
            'value' => 'client',
            'text' => 'Клиент',
        ],
        [
            'value' => 'driver',
            'text' => 'Водитель',
        ],
        [
            'value' => 'worker',
            'text' => 'Работник',
        ]
    ];

    /**
     * @var string[]
     */
    protected array $orderStatuses = [
        [
            'value' => 'completed',
            'text' => 'Завершен',
            'color' => '#00C853'
        ],
        [
            'value' => 'canceled',
            'text' => 'Отменен',
            'color' => '#C62828'
        ],
        [
            'value' => 'in_process',
            'text' => 'В процессе',
            'color' => '#00B0FF'
        ]
    ];

    /**
     * OrderFeedbackService constructor.
     * @param  OrderFeedbackContract  $feedbackContract
     * @param  OrderFeedbackOptionContract  $feedbackOptionContract
     */
    public function __construct(OrderFeedbackContract $feedbackContract, OrderFeedbackOptionContract $feedbackOptionContract)
    {
        $this->feedbackContract = $feedbackContract;
        $this->feedbackOptionContract = $feedbackOptionContract;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getOrderStatuses()
    {
        return $this->orderStatuses;
    }

    /**
     * @return array
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * @param $request
     * @return LengthAwarePaginator
     */
    public function complaintFranchisePaginate($request): LengthAwarePaginator
    {
        $per_page = $request['per_page'] && is_numeric($request['per_page']) ? $request['per_page'] : 25;
        $page = $request['page'] && is_numeric($request['page']) ? $request['page'] : 1;
        $search = ($request['search'] && 'null' !== $request['search']) ? $request['search'] : '';
        $status = $request['status'] && 'null' !== $request['status'] ? $request['status'] : null;
        $type = $request['type'] && 'null' !== $request['type'] ? explode(',', $request['type']) : null;
        $writer = $request['writer'] && 'null' !== $request['writer'] ? explode(',', $request['writer']) : null;

        $feedbacks = $this->feedbackContract->whereHas('order', fn($q) => $q->whereHas('franchise', fn($q) => $q->where('franchise_id', '=', FRANCHISE_ID)));

        if ($search) {
            $this->paginateSearch($feedbacks, $search);
        }

        if ($writer) {
            $this->paginateWriter($feedbacks, $writer);
        }

        if ($status) {
            $this->paginateOrderStatus($feedbacks, $status);
        }

        if ($type) {
            $this->paginateType($feedbacks, $type);
        }

        $paginated = $feedbacks
            ->with([
                'option',
                'writable',
                'readable',
                'order' => fn($q) => $q->with([
                    'carClass',
                    'meet',
                    'car_options',
                    'passenger',
                    'paymentType',
                    'customer',
                    'completed' => fn($q) => $q->with(['driver.driver_info', 'car.park']),
                    'canceled' => fn($q) => $q->with(['driver.driver_info', 'car.park']),
                    'current_shipped' => fn($q) => $q->with(['driver.driver_info', 'driver.car.park', 'status']),
                    'client' => fn($q) => $q->withCount([
                        'orders as completed_orders_count' => fn($q) => $q->whereHas('completed'),
                        'orders as canceled_orders_count' => fn($q) => $q->whereHas('canceled'),
                    ])
                ])
            ])
            ->orderBy('order_feedback_id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        $paginated->map(
            function ($feedback) {
                $feedback['type'] = $this->findType($feedback);
                $feedback['order_status'] = $this->findOrderStatus($feedback->order);
                $feedback['driver'] = $this->findDriver($feedback->order);
                $feedback['car'] = $this->findCar($feedback->order);
                return $feedback;
            }
        );

        return $paginated;
    }

    /**
     * @param $feedbacks
     * @param $search
     * @return mixed
     */
    protected function paginateSearch($feedbacks, $search)
    {
        $feedbacks->where(fn($q) => $q->whereHas('order',
            fn($q) => $q->where('order_id', '=', $search)
                ->orWhereHas('client', $this->nameSearch($search))
                ->orWhereHas('driver.driver_info', $this->nameSearch($search)))
            ->orWhere('text', 'LIKE', '%'.$search.'%'));

        return $feedbacks;
    }

    /**
     * @param $searchText
     * @return Closure
     */
    protected function nameSearch($searchText): callable
    {
        $searchWords = explode(' ', $searchText);

        return static function ($q) use ($searchText, $searchWords) {
            $q->where('name', 'LIKE', '%'.$searchText.'%')
                ->orWhere('surname', 'LIKE', '%'.$searchText.'%')
                ->orWhere('patronymic', 'LIKE', '%'.$searchText.'%');

            foreach ($searchWords as $word) {
                $q->orWhere('name', 'LIKE', '%'.$word.'%')
                    ->orWhere('surname', 'LIKE', '%'.$word.'%')
                    ->orWhere('patronymic', 'LIKE', '%'.$word.'%');
            }
        };
    }

    /**
     * @param $feedbacks
     * @param $writer
     * @return mixed
     */
    protected function paginateWriter($feedbacks, $writer)
    {
        $feedbacks->where(
            function ($q) use ($writer) {
                $or = false;
                if (\in_array("client", $writer, true)) {
                    $q->where('writable_type', '=', (new Client())->getMap());
                    $or = true;
                }
                if (\in_array("driver", $writer, true)) {
                    $or ? $q->orWhere('writable_type', '=', (new Driver())->getMap()) :
                        $q->where('writable_type', '=', (new Driver())->getMap());
                }
                if (\in_array("worker", $writer, true)) {
                    $or ? $q->orWhere('writable_type', '=', (new SystemWorker())->getMap()) :
                        $q->where('writable_type', '=', (new SystemWorker())->getMap());
                }
            }
        );
        return $feedbacks;
    }

    /**
     * @param $feedbacks
     * @param $status
     * @return mixed
     */
    protected function paginateOrderStatus($feedbacks, $status)
    {
        $feedbacks->whereHas('order',
            function ($q) use ($status) {
                if ('completed' === $status) {
                    $q->has('completed');
                }
                if ('canceled' === $status) {
                    $q->has('canceled');
                }
                if ('in_process' === $status) {
                    $q->whereDoesntHave('completed')->whereDoesntHave('canceled');
                }
            });

        return $feedbacks;
    }

    /**
     * @param $feedbacks
     * @param $type
     * @return mixed
     */
    protected function paginateType($feedbacks, $type)
    {
        $feedbacks->where(
            function ($q) use ($type) {
                $or = false;
                if (\in_array("none", $type, true)) {
                    $or ? $q->orWhere('assessment', '=', 0) :
                        $q->where('assessment', '=', 0);
                    $or = true;
                }
                if (\in_array("positive", $type, true)) {
                    $or ? $q->orWhere('assessment', '>', 2) :
                        $q->where('assessment', '>', 2);
                    $or = true;
                }
                if (\in_array("negative", $type, true)) {
                    $or ? $q->orWhereBetween('assessment', [1, 2]) : $q->whereBetween('assessment', [1, 2]);
                }
            });
        return $feedbacks;
    }

    /**
     * @param $feedback
     * @return string[]
     */
    protected function findType($feedback)
    {
        if ($feedback->assessment) {
            return $this->types[array_search($feedback->assessment > 2 ? 'positive' : 'negative', array_column($this->types, 'value'))];
        }

        return null;
    }

    /**
     * @param $order
     * @return string[]
     */
    protected function findOrderStatus($order)
    {
        if ($order->completed) {
            return $this->orderStatuses[array_search('completed', array_column($this->orderStatuses, 'value'))];
        }

        if ($order->canceled) {
            return $this->orderStatuses[array_search('canceled', array_column($this->orderStatuses, 'value'))];
        }

        return $this->orderStatuses[array_search('in_progress', array_column($this->orderStatuses, 'value'))];
    }

    /**
     * @param $order
     * @return mixed
     */
    protected function findDriver($order)
    {
        if ($order->completed && $order->completed->driver) {
            return $order->completed->driver;
        }

        if ($order->canceled && $order->canceled->driver) {
            return $order->canceled->driver;
        }

        if (!$order->canceled &&
            $order->current_shipped &&
            2 === $order->current_shipped->status->status &&
            $order->current_shipped->driver) {
            return $order->current_shipped->driver;
        }

        return null;
    }

    /**
     * @param $order
     * @return |null
     */
    protected function findCar($order)
    {
        if ($order->completed && $order->completed->car) {
            return $order->completed->car;
        }

        if ($order->canceled && $order->canceled->car) {
            return $order->canceled->car;
        }

        if (!$order->canceled &&
            $order->current_shipped &&
            2 === $order->current_shipped->status->status &&
            $order->current_shipped->car) {
            return $order->current_shipped->car;
        }

        return null;
    }

    /**
     * @param  string  $owner
     * @param  bool  $completed
     * @return mixed
     */
    public function getFeedbacks(string $owner, bool $completed = true): Collection
    {
        return $this->feedbackOptionContract
            ->where('completed', '=', $completed)
            ->where('owner_type', '=', $owner)
            ->findAll(['order_feedback_option_id', 'name', 'option', 'assessment']);
    }
}
