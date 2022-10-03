<?php

declare(strict_types=1);


namespace Src\Services\RatingPointService;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use ServiceEntity\BaseService;
use Src\Core\Enums\ConstRatingPattern;
use Src\Models\RatingSystem\EstimatedRating;
use Src\Repositories\Client\ClientContract;
use Src\Repositories\Driver\DriverContract;
use Src\Repositories\DriverRatingLevel\DriverRatingLevelContract;
use Src\Repositories\DriverRatingPattern\DriverRatingPatternContract;
use Src\Repositories\EstimatedRating\EstimatedRatingContract;
use Src\Repositories\Order\OrderContract;
use Src\Repositories\OrderAttach\OrderAttachContract;
use Src\Repositories\OrderCommon\OrderCommonContract;
use Src\Repositories\OrderShippedDriver\OrderShippedDriverContract;
use Src\Repositories\Preorder\PreorderContract;
use Src\Services\Geocode\GeocodeServiceContract;

/**
 * Class RatingPointService
 *
 * @package Src\Services\RatingPointService
 */
final class RatingPointService extends BaseService implements RatingPointServiceContract
{
    /**
     * @var array|int[]
     */
    protected array $data = [
        'rejected' => 0,
        'accepted' => 0,
        'common' => 0,
        'common_rejected' => 0,
        'common_accepted' => 0,
        'added_patterns' => [],
        'remove_patterns' => []
    ];

    /**
     * RatingPointService constructor.
     *
     * @param  DriverContract  $driverContract
     * @param  ClientContract  $clientContract
     * @param  OrderContract  $orderContract
     * @param  DriverRatingPatternContract  $ratingPatternContract
     * @param  OrderShippedDriverContract  $preOrderDataContract
     * @param  DriverRatingLevelContract  $ratingLevelContract
     * @param  GeocodeServiceContract  $geoService
     * @param  EstimatedRatingContract  $estimatedContract
     * @param  PreorderContract  $preOrderContract
     * @param  OrderCommonContract  $commonOrderContract
     * @param  OrderAttachContract  $attachOrderContract
     */
    public function __construct(
        protected DriverContract $driverContract,
        protected ClientContract $clientContract,
        protected OrderContract $orderContract,
        protected DriverRatingPatternContract $ratingPatternContract,
        protected OrderShippedDriverContract $preOrderDataContract,
        protected DriverRatingLevelContract $ratingLevelContract,
        protected GeocodeServiceContract $geoService,
        protected EstimatedRatingContract $estimatedContract,
        protected PreorderContract $preOrderContract,
        protected OrderCommonContract $commonOrderContract,
        protected OrderAttachContract $attachOrderContract
    ) {
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function calculateRating($driver_id, $order_id, $distance = null, $duration = null, bool $multi = true): array
    {
        $this->distancedRating($driver_id, $distance);

        if ($multi) {
            $this->driverIsFavorite($driver_id);

            if ($this->commonOrderContract->where('order_id', '=', $order_id)->exists()
                || $this->preOrderContract->where('order_id', '=', $order_id)->exists()) {
                $this->commonOrderRating($driver_id);
            }

            if ($this->attachOrderContract->where('order_id', '=', $order_id)->exists()) {
                $this->attachOrderRating($driver_id);
            }
        }

        $this->createEstimatedRating($order_id, $driver_id);

        return $this->getData();
    }

    /**
     * @param $driver_id
     * @param $distance
     * @return void
     */
    protected function distancedRating($driver_id, $distance): void
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'rating']);
        $rating = $driver->rating;
        $this->data['common'] = $rating;

        $pattern = $this->ratingPatternContract
            ->where('type', '=', ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue())
            ->orWhere('type', '=', ConstRatingPattern::LARGE_ORDER_REJECTED()->getValue())
            ->orWhere('type', '=', ConstRatingPattern::NEAR_ORDER_ACCEPTED()->getValue())
            ->orWhere('type', '=', ConstRatingPattern::NEAR_ORDER_REJECTED()->getValue())
            ->findAll(['driver_rating_pattern_id', 'type', 'value']);

        if (config('nyt.large_order') < $distance) {
            $this->data['rejected'] += $pattern->where('type', '=', ConstRatingPattern::LARGE_ORDER_REJECTED()->getValue())->first()->value;
            $this->data['accepted'] += $pattern->where('type', '=', ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue())->first()->value;
            $this->data['common_rejected'] = $rating - $pattern->where('type', '=', ConstRatingPattern::LARGE_ORDER_REJECTED()->getValue())->first()->value;
            $this->data['common_accepted'] = $rating + $pattern->where('type', '=', ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue())->first()->value;

            $this->data['added_patterns'][] = $pattern
                ->where('type', '=', ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue())
                ->first()
                ->driver_rating_pattern_id;
            $this->data['remove_patterns'][] = $pattern
                ->where('type', '=', ConstRatingPattern::LARGE_ORDER_REJECTED()->getValue())
                ->first()
                ->driver_rating_pattern_id;
        } else {
            $this->data['rejected'] += $pattern->where('type', '=', ConstRatingPattern::NEAR_ORDER_REJECTED()->getValue())->first()->value;
            $this->data['accepted'] += $pattern->where('type', '=', ConstRatingPattern::NEAR_ORDER_ACCEPTED()->getValue())->first()->value;
            $this->data['common_rejected'] = $rating - $pattern->where('type', '=', ConstRatingPattern::NEAR_ORDER_REJECTED()->getValue())->first()->value;
            $this->data['common_accepted'] = $rating + $pattern->where('type', '=', ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue())->first()->value;

            $this->data['added_patterns'][] = $pattern
                ->where('type', '=', ConstRatingPattern::NEAR_ORDER_ACCEPTED()->getValue())
                ->first()
                ->driver_rating_pattern_id;
            $this->data['remove_patterns'][] = $pattern
                ->where('type', '=', ConstRatingPattern::NEAR_ORDER_REJECTED()->getValue())
                ->first()
                ->driver_rating_pattern_id;
        }
    }

    /**
     * @param $driver_id
     */
    protected function driverIsFavorite($driver_id): void
    {
        $is_favorite = $this->clientContract->whereHas(
            'favoriteDrivers',
            fn(Builder $favorite_query) => $favorite_query->where('client_favorite_driver.driver_id', $driver_id)
        )->exists();

        if (!$is_favorite) {
            return;
        }

        $pattern = $this->ratingPatternContract
            ->where('type', '=', ConstRatingPattern::FAVORITE_DRIVER_ORDER_ACCEPTED()->getValue())
            ->orWhere('type', '=', ConstRatingPattern::FAVORITE_DRIVER_ORDER_REJECTED()->getValue())
            ->findAll(['driver_rating_pattern_id', 'type', 'value']);

        $driver = $this->driverContract->find($driver_id, [$this->driverContract->getKeyName(), 'rating']);

        $this->data['rejected'] += $pattern->where('type', '=', ConstRatingPattern::FAVORITE_DRIVER_ORDER_REJECTED()->getValue())->first()->value;
        $this->data['accepted'] += $pattern->where('type', '=', ConstRatingPattern::FAVORITE_DRIVER_ORDER_ACCEPTED()->getValue())->first()->value;

        $this->data['common_accepted'] = $driver->rating + $pattern->where('type', '=',
                ConstRatingPattern::FAVORITE_DRIVER_ORDER_ACCEPTED()->getValue())->first()->value;
        $this->data['common_rejected'] = $driver->rating - $pattern->where('type', '=',
                ConstRatingPattern::FAVORITE_DRIVER_ORDER_REJECTED()->getValue())->first()->value;
        $this->data['common'] = $driver->rating;

        $this->data['added_patterns'][] = $pattern->where('type', '=',
            ConstRatingPattern::FAVORITE_DRIVER_ORDER_ACCEPTED()->getValue())->first()->driver_rating_pattern_id;
        $this->data['remove_patterns'][] = $pattern->where('type', '=',
            ConstRatingPattern::FAVORITE_DRIVER_ORDER_REJECTED()->getValue())->first()->driver_rating_pattern_id;
    }

    /**
     * @param $driver_id
     */
    public function commonOrderRating($driver_id): void
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'rating']);

        $pattern = $this->ratingPatternContract
            ->where('type', '=', ConstRatingPattern::COMMON_LIST_ACCEPTED()->getValue())
            ->orWhere('type', '=', ConstRatingPattern::COMMON_LIST_REJECTED()->getValue())
            ->findAll(['driver_rating_pattern_id', 'type', 'value']);

        $this->data['accepted'] += $pattern->where('type', '=', ConstRatingPattern::COMMON_LIST_ACCEPTED()->getValue())->first()->value;
        $this->data['rejected'] += $pattern->where('type', '=', ConstRatingPattern::COMMON_LIST_REJECTED()->getValue())->first()->value;
        $this->data['common_accepted'] += $driver->rating + $pattern->where('type', '=',
                ConstRatingPattern::COMMON_LIST_ACCEPTED()->getValue())->first()->value;
        $this->data['common_rejected'] += $driver->rating - $pattern->where('type', '=',
                ConstRatingPattern::COMMON_LIST_REJECTED()->getValue())->first()->value;
        $this->data['common'] = $driver->rating;

        $this->data['added_patterns'][] = $pattern->where('type', '=',
            ConstRatingPattern::COMMON_LIST_ACCEPTED()->getValue())->first()->driver_rating_pattern_id;
        $this->data['remove_patterns'][] = $pattern->where('type', '=',
            ConstRatingPattern::COMMON_LIST_REJECTED()->getValue())->first()->driver_rating_pattern_id;
    }

    /**
     * @param $driver_id
     */
    protected function attachOrderRating($driver_id): void
    {
        $driver = $this->driverContract->find($driver_id, ['driver_id', 'rating']);

        $pattern = $this->ratingPatternContract
            ->where('type', '=', ConstRatingPattern::ATTACH_ORDER_ACCEPTED()->getValue())
            ->orWhere('type', '=', ConstRatingPattern::ATTACH_ORDER_REJECTED()->getValue())
            ->findAll(['driver_rating_pattern_id', 'type', 'value']);

        $this->data['accepted'] += $pattern->where('type', '=', ConstRatingPattern::ATTACH_ORDER_ACCEPTED()->getValue())->first()->value;
        $this->data['rejected'] += $pattern->where('type', '=', ConstRatingPattern::ATTACH_ORDER_REJECTED()->getValue())->first()->value;
        $this->data['common_accepted'] += $driver->rating + $pattern->where('type', '=',
                ConstRatingPattern::ATTACH_ORDER_ACCEPTED()->getValue())->first()->value;
        $this->data['common_rejected'] += $driver->rating - $pattern->where('type', '=',
                ConstRatingPattern::ATTACH_ORDER_REJECTED()->getValue())->first()->value;
        $this->data['common'] = $driver->rating;

        $this->data['added_patterns'][] = $pattern->where('type', '=',
            ConstRatingPattern::ATTACH_ORDER_ACCEPTED()->getValue())->first()->driver_rating_pattern_id;
        $this->data['remove_patterns'][] = $pattern->where('type', '=',
            ConstRatingPattern::ATTACH_ORDER_REJECTED()->getValue())->first()->driver_rating_pattern_id;
    }

    /**
     * @param $order_id
     * @param $driver_id
     */
    protected function createEstimatedRating($order_id, $driver_id): void
    {
        $this->estimatedContract
            ->updateOrCreate(['order_id', '=', $order_id, 'driver_id', '=', $driver_id],
                [
                    'order_id' => $order_id,
                    'driver_id' => $driver_id,

                    'added_rating' => $this->data['accepted'],
                    'remove_rating' => $this->data['rejected'],

                    'added_patterns' => ['ids' => array_unique(array_flatten_adjustable($this->data['added_patterns']))],
                    'remove_patterns' => ['ids' => array_unique(array_flatten_adjustable($this->data['remove_patterns']))],
                ]
            );

        $estimated_id = $this->estimatedContract
            ->where('order_id', '=', $order_id)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['estimated_rating_id'])
            ->estimated_rating_id;

        $this->data['estimated_rating_id'] = $estimated_id;
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        $data = $this->data;

        $this->data = [
            'rejected' => 0,
            'accepted' => 0,
            'common' => 0,
            'common_rejected' => 0,
            'common_accepted' => 0,
            'added_patterns' => [],
            'remove_patterns' => []
        ];

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function setDriverRating($driver_id, $order_id, $patterns): Collection
    {
        $rating = null;
        $level = null;
        $patterns = \is_array($patterns) ? $patterns : [$patterns];

        foreach ($patterns as $pattern) {
            switch ($pattern) {
                case ConstRatingPattern::FAVORITE_DRIVER_ORDER_ACCEPTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::FAVORITE_DRIVER_ORDER_ACCEPTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::FAVORITE_DRIVER_ORDER_REJECTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::FAVORITE_DRIVER_ORDER_REJECTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::NEAR_ORDER_ACCEPTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::NEAR_ORDER_ACCEPTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::NEAR_ORDER_REJECTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::NEAR_ORDER_REJECTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::LARGE_ORDER_ACCEPTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::LARGE_ORDER_REJECTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::LARGE_ORDER_REJECTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::COMMON_LIST_ACCEPTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::COMMON_LIST_ACCEPTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::COMMON_LIST_REJECTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::COMMON_LIST_REJECTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::ADDRESS_ORDER_ACCEPTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::ADDRESS_ORDER_ACCEPTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::ADDRESS_ORDER_REJECTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::ADDRESS_ORDER_REJECTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::RESET_AFTER_ACCEPT()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::RESET_AFTER_ACCEPT()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                case ConstRatingPattern::PREORDER_REJECTED()->getValue():
                    $rating = $this->ratingPatternContract->where('type', '=', ConstRatingPattern::PREORDER_REJECTED()->getValue())->findFirst();
                    $level = $this->updateRating($driver_id, $order_id, $rating->value, $rating->symbol);
                    break;
                default:
            }
        }

        return $this->parseResult($instance, ['rating', 'pattern', 'level'], [$rating->value, $rating->driver_rating_pattern_id, $level]);
    }

    /**
     * @param $driver_id
     * @param $order_id
     * @param $rating
     * @param $increment_decrement
     * @return string
     */
    protected function updateRating($driver_id, $order_id, $rating, $increment_decrement): string
    {
        $driver = $this->driverContract->where($this->driverContract->getKeyName(), '=', $driver_id)->findFirst(['driver_id', 'rating']);

        $this->estimatedContract
            ->where('driver_id', '=', $driver_id)
            ->where('order_id', '=', $order_id)
            ->updateSet(['outcome' => 'increment' === $increment_decrement ? 'added' : 'removed']);

        $driver->{$increment_decrement}('rating', $rating);

        $level = $this->ratingLevelContract
            ->where('from', '<=', $driver->rating + $rating)
            ->where('before', '>=', $driver->rating + $rating)
            ->findFirst();

        $this->driverContract->update($driver_id, ['rating_level_id' => $level->driver_rating_level_id]);

        return $level->level;
    }

    /**
     * @param $order_id
     * @param $driver_id
     * @return mixed
     */
    public function getRating($order_id, $driver_id): ?EstimatedRating
    {
        return $this->estimatedContract
            ->where('order_id', '=', $order_id)
            ->where('driver_id', '=', $driver_id)
            ->findFirst(['estimated_rating_id', 'order_id', 'driver_id', 'remove_rating']);
    }
}
