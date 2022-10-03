<?php

declare(strict_types=1);


namespace Src\Core\TransLiterate;


/**
 * Class EnglishToRussian
 * @package Src\Core\Transliterate
 */
abstract class EnglishToRussian
{
    /** @const string[] */
    private const ENGLISH_TO_RUSSIAN_RULES = [
        'a' => 'а',
        'b' => 'б',
        'v' => 'в',
        'g' => 'г',
        'd' => 'д',
        'е' => 'e',
        'zh' => 'ж',
        'z'=>'з',
        'i'=>'и',
        'y'=>'й',
        'k'=>'к',
        'l'=>'л' ,
        'm' => 'м',
        'n' => 'н',
        'o' => 'о',
        'p' => 'п',
        'r' => 'р',
        's' => 'с',
        't' => 'т',
        'u' => 'у',
        'f' => 'ф',
        'h' => 'х',
        'ts' => 'ц',
        'ch' => 'ч',
        'sh' => 'ш',
        'sht' => 'щ',
        '' => 'ь',
        'ы' => 'y',
        'ъ' => '',
        'e' => 'э',
        'yu' => 'ю',
        'ya' => 'я',
        'A' => 'А',
        'B' => 'Б',
        'V' => 'В',
        'G' => 'Г',
        'D' => 'Д',
        'E' => 'Е',
        'Zh' => 'Ж',
        'Z' => 'З',
        'I' => 'И',
        'Y' => 'Й',
        'K' => 'К',
        'L' => 'Л',
        'M' => 'М',
        'N' => 'Н',
        'O' => 'О',
        'P' => 'П',
        'R' => 'Р',
        'S' => 'С',
        'T' => 'Т',
        'U' => 'У',
        'F' => 'Ф',
        'H' => 'Х',
        'Ts' => 'Ц',
        'Ch' => 'Ч',
        'Sh' => 'Ш',
        'Sht' => 'Щ',
        'Yu' => 'Ю',
        'Ya' => 'Я',
        "'" => '',
    ];

    /**
     * @param  string  $russianText
     *
     * @return string
     */
    final public static function transliterate(string $russianText): string
    {
        $transliteratedText = '';

        if ('' !== $russianText) {
            $transliteratedText = \str_replace(
                \array_keys(self::ENGLISH_TO_RUSSIAN_RULES),
                \array_values(self::ENGLISH_TO_RUSSIAN_RULES),
                $russianText
            );
        }

        return $transliteratedText;
    }
}
