<?php

declare(strict_types=1);


namespace Src\Core\Socket\Provider;


use Illuminate\Database\Eloquent\Model;
use Src\Core\Enums\ConstQueue;
use Src\Jobs\Tcp;

/**
 * Class MessageProvider
 * @package Src\Core\Socket\Messages
 */
class MessageProvider
{
    /**
     * MessageProvider constructor.
     * @param  string  $routeFile
     * @param  array  $routeData
     * @param  Model  $user
     * @param  object  $data
     */
    public function __construct(protected string $routeFile, protected array $routeData, protected Model $user, protected object $data)
    {
    }

    /**
     *
     */
    public function routeProvider(): void
    {
        $routes = require base_path("routes/socket/$this->routeFile.php");
        $class = array_keys($routes[$this->routeData['url']])[0];

        if (!class_exists($class)) {
            return;
        }

        Tcp::dispatch($this->user, $this->data, $class, $routes, $this->routeData)->onQueue(ConstQueue::TCP()->getValue());
    }
}
