<?php

declare(strict_types=1);

namespace Repository\Elastica\Payloads;

use Exception;
use Illuminate\Database\Eloquent\Model;

use function get_class;

class DocumentPayload extends TypePayload
{
    /**
     * DocumentPayload constructor.
     *
     * @param  Model  $model
     * @return void
     * @throws Exception
     */
    public function __construct(Model $model)
    {
        if (null === $model->getScoutKey()) {
            throw new Exception(sprintf(
                'The key value must be set to construct a payload for the %s instance.',
                get_class($model)
            ));
        }

        parent::__construct($model);

        $this->payload['id'] = $model->getScoutKey();
        $this->protectedKeys[] = 'id';
    }
}
