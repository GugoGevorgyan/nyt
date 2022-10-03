<?php

namespace Repository\Elastica\Console;

use Exception;
use Illuminate\Console\Command;
use LogicException;
use Repository\Elastica\Facades\ElasticClient;
use Repository\Elastica\Payloads\IndexPayload;
use Repository\Elastica\Payloads\RawPayload;
use Repository\Traits\Migratable;
use Repository\Traits\RequiresIndexConfiguratorArgument;

class ElasticIndexUpdateCommand extends Command
{
    use RequiresIndexConfiguratorArgument;

    /**
     * {@inheritdoc}
     */
    protected $name = 'elastic:update-index';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Update settings and mappings of an Elasticsearch index';

    /**
     * Handle the command.
     *
     * @var string
     */
    public function handle()
    {
        $this->updateIndex();

        $this->createWriteAlias();
    }

    /**
     * Update the index.
     *
     * @return void
     * @throws Exception
     */
    protected function updateIndex()
    {
        $configurator = $this->getIndexConfigurator();

        $indexPayload = (new IndexPayload($configurator))->get();

        $indices = ElasticClient::indices();

        if (!$indices->exists($indexPayload)) {
            throw new LogicException(sprintf(
                'Index %s doesn\'t exist',
                $configurator->getName()
            ));
        }

        try {
            $indices->close($indexPayload);

            if ($settings = $configurator->getSettings()) {
                $indexSettingsPayload = (new IndexPayload($configurator))
                    ->set('body.settings', $settings)
                    ->get();

                $indices->putSettings($indexSettingsPayload);
            }

            $indices->open($indexPayload);
        } catch (Exception $exception) {
            $indices->open($indexPayload);

            throw $exception;
        }

        $this->info(sprintf(
            'The index %s was updated!',
            $configurator->getName()
        ));
    }

    /**
     * Create a write alias.
     *
     * @return void
     */
    protected function createWriteAlias()
    {
        $configurator = $this->getIndexConfigurator();

        if (!in_array(Migratable::class, class_uses_recursive($configurator))) {
            return;
        }

        $indices = ElasticClient::indices();

        $existsPayload = (new RawPayload())
            ->set('name', $configurator->getWriteAlias())
            ->get();

        if ($indices->existsAlias($existsPayload)) {
            return;
        }

        $putPayload = (new IndexPayload($configurator))
            ->set('name', $configurator->getWriteAlias())
            ->get();

        $indices->putAlias($putPayload);

        $this->info(sprintf(
            'The %s alias for the %s index was created!',
            $configurator->getWriteAlias(),
            $configurator->getName()
        ));
    }
}
