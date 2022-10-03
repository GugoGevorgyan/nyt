<?php

declare(strict_types=1);


namespace Src\Repositories\Image;


use Illuminate\Contracts\Container\Container;
use Repository\Repositories\BaseRepository;
use Src\Models\Views\Image;

/**
 * Class ImageRepository
 * @package Src\Repositories\Image
 */
class ImageRepository extends BaseRepository implements ImageContract
{
    /**
     * CarRepository constructor.
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container)
            ->setModel(Image::class)
            ->setCacheDriver('redis')
            ->setRepositoryId('images');
    }
}
