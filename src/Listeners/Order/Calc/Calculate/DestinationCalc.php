<?php

declare(strict_types=1);

namespace Src\Listeners\Order\Calc\Calculate;

use Src\Core\Contracts\Realizing;
use Src\Listeners\Order\Calc\Contracts\CounterInterface;
use Src\Listeners\Order\Calc\Contracts\DecorateInterface;
use Src\Models\Order\OrderProcess;

/**
 *
 */
class DestinationCalc implements DecorateInterface
{
    /**
     * @var CounterInterface|null
     */
    protected Realiz1ing|null $app = null;

    /**
     * @param  Realizing  $app
     * @return static
     */
    public static function decorate(Realizing $app): self
    {
        return (clone app(self::class))->setApp($app);
    }

    /**
     * @param  Realizing  $app
     * @return DestinationCalc
     */
    public function setApp(Realizing $app): static
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @param  array  $last_cords
     * @param  OrderProcess  $process
     */
    public function calculate(array $last_cords, OrderProcess $process): void
    {
        $this->init($process);
    }

    /**
     * @return void
     */
    public function annul(): void
    {
        $this->app = null;
    }

    /**
     * @param $process
     */
    protected function init($process): void
    {
        $result = ['price' => 0, 'calculate_price' => 0, 'increment_price' => 0];

        $this->app->savePriceData($process, $result, $this->app->driver['current_order_stages']['started']);
    }
}
