<?php

namespace Repository\Elastica\Indexers;

use Illuminate\Database\Eloquent\Collection;
use Repository\Elastica\Facades\ElasticClient;
use Repository\Elastica\Payloads\DocumentPayload;
use Repository\Traits\Migratable;

class SingleIndexer implements IndexerInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(Collection $models)
    {
        $models->each(function ($model) {
            if ($model::usesSoftDelete() && config('scout.soft_delete', false)) {
                $model->pushSoftDeleteMetadata();
            }

            $modelData = array_merge(
                $model->toSearchableArray(),
                $model->scoutMetadata()
            );

            if (empty($modelData)) {
                return true;
            }

            $indexConfigurator = $model->getIndexConfigurator();

            $payload = (new DocumentPayload($model))
                ->set('body', $modelData);

            if (in_array(Migratable::class, class_uses_recursive($indexConfigurator))) {
                $payload->useAlias('write');
            }

            if ($documentRefresh = config('scout_elastic.document_refresh')) {
                $payload->set('refresh', $documentRefresh);
            }

            ElasticClient::index($payload->get());
        });
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Collection $models)
    {
        $models->each(function ($model) {
            $payload = new DocumentPayload($model);

            if ($documentRefresh = config('scout_elastic.document_refresh')) {
                $payload->set('refresh', $documentRefresh);
            }

            $payload->set('client.ignore', 404);

            ElasticClient::delete($payload->get());
        });
    }
}
