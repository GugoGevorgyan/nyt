<?php

declare(strict_types=1);


namespace Src\Services\Tariff\Traits;


use Src\Exceptions\Lexcept;
use Src\Models\Order\PaymentType;

/**
 * Trait Util
 * @package Src\Services\Tariff\Traits
 */
trait Util
{
    /**
     * @var array
     */
    protected array $fromCoordinates;
    /**
     * @var int
     */
    protected int $carClassId;
    /**
     * @var array|null
     */
    protected ?array $toCoordinates = null;
    /**
     * @var bool
     */
    protected bool $payCompany = false;
    /**
     * @var int|null
     */
    protected ?int $payCompanyId = null;
    /**
     * @var array
     */
    protected array $demands = [];
    /**
     * @var string
     */
    protected string $province = '';
    /**
     * @var string
     */
    protected string $locality = '';
    /**
     * @var string|null
     */
    protected ?string $orderTime = null;
    /**
     * @var int|null
     */
    protected ?int $regionId = null;
    /**
     * @var int|null
     */
    protected ?int $cityId = null;
    /**
     * @var bool
     */
    protected bool $isRent = false;
    /**
     * @var int
     */
    protected int $rentTime = 0;

    /**
     * @param  array  $from
     * @param  array  $to
     * @param  array  $options
     * @param  bool  $is_rent
     */
    protected function setData(array $from, array $to = [], array $options = [], bool $is_rent = false): void
    {
        $this->fromCoordinates = ['lat' => $from[0] ?? $from['lat'], 'lut' => $from[1] ?? $from['lut']];
        $this->toCoordinates = $to ? ['lat' => $to[0] ?? $to['lat'], 'lut' => $to[1] ?? $to['lut']] : [];

        $session = session('locality');
        $this->province = (string)$session['province'];
        $this->locality = (string)$session['locality'];

        if ($is_rent) {
            $this->isRent = true;
            $this->rentTime = $options['rent_time'];
        }

        $this->regionId = session('order.from_region_id') ?: null;
        $this->cityId = session('order.from_city_id') ?: null;

        if ($options) {
            $this->payCompanyId = $options['payment_type_company'] ?? null;
            $this->carClassId = $options['car_class'];
            $this->orderTime = $options['time'] ?? null;
            $this->demands = $options['demands'];
        }
    }

    /**
     * @param $payment_type
     */
    protected function isCompany($payment_type): void
    {
        $payments = $this->paymentTypeContract->where('type', '=', $payment_type)->findFirst(['type']);
        $this->payCompany = $payments->type === PaymentType::COMPANY;
    }

    /**
     * @return array|null
     * @throws Lexcept
     */
    protected function masterTariff(): ?array
    {
        if ($this->isRent) {
            $tariff = $this->getRentTariff();
        } elseif ($this->payCompany) {
            $tariff = $this->getCompanyTariff();
        } else {
            $tariff = $this->getClientTariff();
        }

        return $tariff;
    }

    /**
     * @return array
     */
    protected function getCompanyUnRegionalTariffs(): array
    {
        return $this->tariffContract
            ->where('payment_type_id', '=', PaymentType::getTypeId(PaymentType::COMPANY))
            ->whereJsonLength('region->id', null, '=')
            ->with('tariff_type')
            ->findAll()
            ->toArray();
    }

    /**
     * @param $region_id
     * @param $city_ids
     * @return array
     */
    protected function getCompanyRegionalTariffs($region_id, $city_ids): array
    {
        return $this->tariffContract
            ->whereJsonContains('region->ids', $region_id)
            ->where('payment_type_id', '=', PaymentType::getTypeId(PaymentType::COMPANY))
            ->where(fn($q) => $q->whereHas('cities', fn($q) => $q->whereIn('city_id', $city_ids))->orWhere('city->ids', '=', '[]'))
            ->with('tariff_type')
            ->findAll()
            ->toArray();
    }
}
