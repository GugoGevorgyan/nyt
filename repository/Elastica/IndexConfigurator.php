<?php

declare(strict_types=1);

namespace Repository\Elastica;

use Illuminate\Support\Str;

abstract class IndexConfigurator
{
    /**
     * The name.
     *
     * @var string
     */
    protected $name;

    /**
     * The settings.
     *
     * @var array
     */
    protected $settings = [];

    /**
     * The default mapping.
     *
     * @var array
     */
    protected $defaultMapping = [];

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName()
    {
        $suffix = $this->name ?? Str::snake(str_replace('IndexConfigurator', '', class_basename($this)));

        return config('scout.prefix').$suffix;
    }

    /**
     * Get the settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @deprecated
     */
    public function getDefaultMapping()
    {
        return $this->defaultMapping;
    }
}
