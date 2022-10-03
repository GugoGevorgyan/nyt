<?php

declare(strict_types=1);

namespace Repository\Providers;

use _PHPStan_70b6e53dc\Nette\InvalidArgumentException;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Repository\Contracts\BaseRepositoryContract;
use Repository\Elastica\Console\ElasticIndexCreateCommand;
use Repository\Elastica\Console\ElasticIndexDropCommand;
use Repository\Elastica\Console\ElasticIndexUpdateCommand;
use Repository\Elastica\Console\ElasticMigrateModelCommand;
use Repository\Elastica\Console\ElasticUpdateMappingCommand;
use Repository\Elastica\Console\IndexConfiguratorMakeCommand;
use Repository\Elastica\Console\SearchableModelMakeCommand;
use Repository\Elastica\Console\SearchRuleMakeCommand;
use Repository\Elastica\ElasticEngine;
use Repository\Generators\Commands\BindingsCommand;
use Repository\Generators\Commands\CriteriaCommand;
use Repository\Generators\Commands\EntityCommand;
use Repository\Generators\Commands\RepositoryCommand;
use Repository\Listeners\RepositoryEventListener;
use Repository\Repositories\BaseRepository;

use function dirname;

/**
 * Class RepositoryModelProvider
 * @package Repository\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * The repository alias pattern.
     *
     * @var string
     */
    protected string $repositoryAliasPattern = '{{class}}Contract';

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bindIf(BaseRepositoryContract::class, BaseRepository::class);

        // Register the event listener
        $this->app->bind('repository.listener', RepositoryEventListener::class);

        $this->commands(RepositoryCommand::class);
        $this->commands(EntityCommand::class);
        $this->commands(BindingsCommand::class);
        $this->commands(CriteriaCommand::class);

        // Register Elastic
        $this->app->singleton('scout_elastic.client', function () {
            $config = Config::get('scout_elastic.client');
            return ClientBuilder::fromConfig($config);
        });
    }

    public function boot(): void
    {
        // Merge config
        if (File::exists(dirname(__DIR__).DS.'config'.DS.'config.php')) {
            $this->mergeConfigFrom(dirname(__DIR__).DS.'config'.DS.'config.php', 'repository');
            // Publish config
            $this->publishesConfig('bugover/laravel-repositories');
        }

        // Subscribe the registered event listener
        /** @noinspection OffsetOperationsInspection */
        $this->app['events']->subscribe('repository.listener');

        // Register elastica
        $this->elastica();
    }

    protected function elastica()
    {
        $this->commands([
            // make commands
            IndexConfiguratorMakeCommand::class,
            SearchableModelMakeCommand::class,
            SearchRuleMakeCommand::class,

            // elastic commands
            ElasticIndexCreateCommand::class,
            ElasticIndexUpdateCommand::class,
            ElasticIndexDropCommand::class,
            ElasticUpdateMappingCommand::class,
            ElasticMigrateModelCommand::class,
        ]);

        $this
            ->app
            ->make(EngineManager::class)
            ->extend('elastic', function () {
                $indexerType = config('scout_elastic.indexer', 'single');
                $updateMapping = config('scout_elastic.update_mapping', true);

                $indexerClass = '\\Repository\Elastica\\Indexers\\'.ucfirst($indexerType).'Indexer';

                if (!class_exists($indexerClass)) {
                    throw new InvalidArgumentException(sprintf(
                        'The %s indexer doesn\'t exist.',
                        $indexerType
                    ));
                }

                return new ElasticEngine(new $indexerClass(), $updateMapping);
            });
    }
}
