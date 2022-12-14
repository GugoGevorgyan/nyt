<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

/**
 * Class AppName
 * @package Src\Console\Commands
 */
class AppName extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'app:name';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the application namespace';

    /**
     * Current root application namespace.
     *
     * @var string
     */
    protected string $currentRoot = '';

    /**
     * Create a new key generator command.
     *
     * @param  Composer  $composer
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(protected Composer $composer, protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $this->currentRoot = trim($this->laravel->getNamespace(), '\\');

        $this->setAppDirectoryNamespace();
        $this->setBootstrapNamespaces();
        $this->setConfigNamespaces();
        $this->setComposerNamespace();
        $this->setDatabaseFactoryNamespaces();

        $this->info('Application namespace set!');

        $this->composer->dumpAutoloads();

        $this->call('optimize:clear');
    }

    /**
     * Set the namespace on the files in the app directory.
     *
     * @return void
     */
    protected function setAppDirectoryNamespace(): void
    {
        $files = Finder::create()
            ->in($this->laravel['path'])
            ->contains($this->currentRoot)
            ->name('*.php');

        foreach ($files as $file) {
            $this->replaceNamespace($file->getRealPath());
        }
    }

    /**
     * Replace the App namespace at the given path.
     *
     * @param  string  $path
     * @return void
     */
    protected function replaceNamespace($path): void
    {
        $search = [
            'namespace '.$this->currentRoot.';',
            $this->currentRoot.'\\',
        ];

        $replace = [
            'namespace '.$this->argument('name').';',
            $this->argument('name').'\\',
        ];

        $this->replaceIn($path, $search, $replace);
    }

    /**
     * Replace the given string in the given file.
     *
     * @param  string  $path
     * @param  string|array  $search
     * @param  string|array  $replace
     * @return void
     * @throws FileNotFoundException
     */
    protected function replaceIn($path, $search, $replace): void
    {
        if ($this->files->exists($path)) {
            $this->files->put($path, str_replace($search, $replace, $this->files->get($path)));
        }
    }

    /**
     * Set the bootstrap namespaces.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function setBootstrapNamespaces(): void
    {
        $search = [
            $this->currentRoot.'\\Http',
            $this->currentRoot.'\\Console',
            $this->currentRoot.'\\Exceptions',
        ];

        $replace = [
            $this->argument('name').'\\Http',
            $this->argument('name').'\\Console',
            $this->argument('name').'\\Exceptions',
        ];

        $this->replaceIn($this->getBootstrapPath(), $search, $replace);
    }

    /**
     * Get the path to the bootstrap/app.php file.
     *
     * @return string
     */
    protected function getBootstrapPath(): string
    {
        return $this->laravel->bootstrapPath().'/app.php';
    }

    /**
     * Set the namespace in the appropriate configuration files.
     *
     * @return void
     */
    protected function setConfigNamespaces(): void
    {
        $this->setAppConfigNamespaces();
        $this->setAuthConfigNamespace();
        $this->setServicesConfigNamespace();
    }

    /**
     * Set the application provider namespaces.
     *
     * @return void
     */
    protected function setAppConfigNamespaces(): void
    {
        $search = [
            $this->currentRoot.'\\Providers',
            $this->currentRoot.'\\Http\\Controllers\\',
        ];

        $replace = [
            $this->argument('name').'\\Providers',
            $this->argument('name').'\\Http\\Controllers\\',
        ];

        $this->replaceIn($this->getConfigPath('app'), $search, $replace);
    }

    /**
     * Get the path to the given configuration file.
     *
     * @param  string  $name
     * @return string
     */
    protected function getConfigPath($name): string
    {
        return $this->laravel['path.config'].'/'.$name.'.php';
    }

    /**
     * Set the authentication User namespace.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function setAuthConfigNamespace(): void
    {
        $this->replaceIn(
            $this->getConfigPath('auth'),
            $this->currentRoot.'\\User',
            $this->argument('name').'\\User'
        );
    }

    /**
     * Set the services User namespace.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function setServicesConfigNamespace(): void
    {
        $this->replaceIn(
            $this->getConfigPath('services'),
            $this->currentRoot.'\\User',
            $this->argument('name').'\\User'
        );
    }

    /**
     * Set the PSR-4 namespace in the Composer file.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function setComposerNamespace(): void
    {
        $this->replaceIn(
            $this->getComposerPath(),
            str_replace('\\', '\\\\', $this->currentRoot).'\\\\',
            str_replace('\\', '\\\\', $this->argument('name')).'\\\\'
        );
    }

    /**
     * Get the path to the Composer.json file.
     *
     * @return string
     */
    protected function getComposerPath(): string
    {
        return base_path('composer.json');
    }

    /**
     * Set the namespace in database factory files.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function setDatabaseFactoryNamespaces(): void
    {
        $files = Finder::create()
            ->in(database_path('factories'))
            ->contains($this->currentRoot)
            ->name('*.php');

        foreach ($files as $file) {
            $this->replaceIn(
                $file->getRealPath(),
                $this->currentRoot,
                $this->argument('name')
            );
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The desired namespace'],
        ];
    }
}
