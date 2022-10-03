<?php

declare(strict_types=1);

namespace App\Configures;

use Repository\Elastica\IndexConfigurator as BaseIndex;

/**
 * Class IndexConfigurator
 * @package App
 */
class IndexConfigurator extends BaseIndex
{
    protected $name = 'my_index';

    // You can specify any settings you want, for example, analyzers.
    protected $settings = [
        'analysis' => [
            'analyzer' => [
                'es_std' => [
                    'type' => 'standard',
                    'stopwords' => '_english_'
                ]
            ]
        ]
    ];
}
