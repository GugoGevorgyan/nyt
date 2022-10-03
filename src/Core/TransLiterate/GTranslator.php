<?php

declare(strict_types=1);

namespace Src\Core\TransLiterate;

use Exception;

use function strlen;

/**
 * Google translate text
 */
class GTranslator
{
    /**
     * @var string
     */
    private static string $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&dt=t';

    /**
     * @param  string  $source
     * @param  string  $target
     * @param  string  $text
     * @return string
     * @throws Exception
     */
    public static function translate(string $source = 'auto', string $target = 'ru', string $text = ''): string
    {
        // Request translation
        $response = self::requestTranslation($source, $target, $text);

        // Clean translation
        return self::getSentencesFromJSON($response);
    }

    /**
     * @param $source
     * @param $target
     * @param $text
     * @return string|array
     */
    protected static function requestTranslation($source, $target, $text): string|array
    {
        if (strlen($text) >= 5000) {
            throw new \RuntimeException(trans('validation.maximum_characters_5000'));
        }

        $fields = [
            'sl' => urlencode($source),
            'tl' => urlencode($target),
            'q' => urlencode($text)
        ];

        // URL-ify the data for the POST
        $fields_string = '';

        foreach ($fields as $key => $value) {
            $fields_string .= '&'.$key.'='.$value;
        }

        rtrim($fields_string, '&');

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::$url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        return $result;
    }

    /**
     * @param $json
     * @return string
     * @throws Exception
     */
    protected static function getSentencesFromJSON($json): string
    {
        $sentencesArray = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        $sentences = '';

        if (!$sentencesArray || !isset($sentencesArray[0])) {
            throw new \RuntimeException(trans('validation.google_unusual_traffic'));
        }

        foreach ($sentencesArray[0] as $s) {
            $sentences .= $s[0] ?? '';
        }

        return $sentences;
    }
}
