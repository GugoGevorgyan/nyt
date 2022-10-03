<?php

declare(strict_types=1);

namespace Src\Core\Contracts;

/**
 * Interface TransLiteratorInterface
 * @package Src\Core\Contracts
 */
interface TransLiteratorInterface
{
    /**
     * @param  string  $textToTransliterate
     *
     * @return string
     */
    public static function transliterate(string $textToTransliterate): string;
}
