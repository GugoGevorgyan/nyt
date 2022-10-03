<?php

declare(strict_types=1);

namespace Src\Core\TransLiterate;

/**
 * Class TransLiterate
 * @package Src\Core\TransLiterate
 */
class TransLiterate
{
    /**
     * @param  string  $russianText
     *
     * @return string
     */
    public function ruToEn(string $russianText): string
    {
        return RussianToEnglish::transliterate($russianText);
    }

    /**
     * @param  string  $russianText
     *
     * @return string
     */
    public function enToRu(string $russianText): string
    {
        return EnglishToRussian::transliterate($russianText);
    }
}
