<?php

declare(strict_types=1);

define('LARAVEL_START', microtime(true));

use Illuminate\Foundation\Application;

new class {
    /**
     *  Turn the light on.
     */
    public function __construct()
    {
        $this->registerComposerAutoload();
        $app = $this->makeApplication();
        $this->boot($app);
    }

    /**
     * Find and load Composer autoload.
     */
    protected function registerComposerAutoload(): void
    {
        $autoload_files = [__DIR__.'/../../vendor/autoload.php'];

        $autoload_file = current(array_filter($autoload_files, static fn(string $path) => file_exists($path)));

        false === $autoload_file ? throw new RuntimeException(trans('messages.composer_autoload_not_found'), 504) : null;

        require $autoload_file;
    }

    /**
     * Boot an application.
     *
     * @param  Application  $app
     */
    protected function boot(Application $app): void
    {
        $app[Illuminate\Contracts\Console\Kernel::class]->bootstrap();
    }

    /**
     * Make an application and register services.
     *
     * @return Application
     */
    protected function makeApplication(): Application
    {
        if (!file_exists($basePath = $_SERVER['argv'][4] ?? $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__, 2))) {
            throw new InvalidArgumentException(trans('messages.no_application_base_path_provided_in_child_process'));
        }

        $app = new Application($basePath);
        $app->useAppPath(__DIR__.'/../../src');

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            class_exists(\App\Kernels\Console::class) ? \App\Kernels\Console::class : \Illuminate\Foundation\Console\Kernel::class
        );
        $app->singleton(
            \Illuminate\Contracts\Http\Kernel::class,
            class_exists(\App\Kernels\Http::class) ? \App\Kernels\Http::class : \Illuminate\Foundation\Http\Kernel::class
        );
        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            class_exists(\App\Handler::class) ? \App\Handler::class : \Illuminate\Foundation\Exceptions\Handler::class
        );

        return $app;
    }
};
