<?php

declare(strict_types=1);

namespace Repository\Elastica\Console;

use Illuminate\Console\Command;
use Repository\Elastica\Facades\ElasticClient;
use Repository\Elastica\Payloads\IndexPayload;
use Repository\Traits\Migratable;
use Repository\Traits\RequiresIndexConfiguratorArgument;

class ElasticIndexCreateCommand extends Command
{
    use RequiresIndexConfiguratorArgument;

    /**
     * {@inheritdoc}
     */
    protected $name = 'elastic:create-index';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Create an Elasticsearch index';

    /**
     * Handle the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->createIndex();

        $this->createWriteAlias();
    }

    /**
     * Create an index.
     *
     * @return void
     */
    protected function createIndex()
    {
        $configurator = $this->getIndexConfigurator();

        $payload = (new IndexPayload($configurator))
            ->setIfNotEmpty('body.settings', $configurator->getSettings())
            ->get();

        ElasticClient::indices()
            ->create($payload);

        $this->info(sprintf(
            'The %s index was created!',
            $configurator->getName()
        ));
    }

    /**
     * Create an write alias.
     *
     * @return void
     */
    protected function createWriteAlias()
    {
        $configurator = $this->getIndexConfigurator();

        if (!in_array(Migratable::class, class_uses_recursive($configurator))) {
            return;
        }

        $payload = (new IndexPayload($configurator))
            ->set('name', $configurator->getWriteAlias())
            ->get();

        ElasticClient::indices()
            ->putAlias($payload);

        $this->info(sprintf(
            'The %s alias for the %s index was created!',
            $configurator->getWriteAlias(),
            $configurator->getName()
        ));
    }
}
