<?php

declare(strict_types=1);


namespace Src\Core\Utils;

use function strlen;

/**
 *
 */
class JsonMin
{
    /**
     * @var string
     */
    protected string $minifiedJson = '';

    /**
     * @param string $originalJson
     */
    public function __construct(protected string $originalJson)
    {
        /** @noinspection MagicMethodsValidityInspection */
        return $this;
    }

    /**
     * @return $this
     */
    public function printMin(): JsonMin
    {
        echo $this->getMin();
        return $this;
    }

    /**
     * @return string
     */
    public function getMin(): string
    {
        $this->minifiedJson = $this::minify($this->originalJson);
        return $this->minifiedJson;
    }

    /**
     * @param $json
     * @return string
     */
    public static function minify($json): string
    {
        $tokenizer = "/\"|(\/\*)|(\*\/)|(\/\/)|\n|\r/";
        $in_string = false;
        $in_multiline_comment = false;
        $in_singleline_comment = false;
        $tmp;
        $tmp2;
        $new_str = [];
        $ns = 0;
        $from = 0;
        $lc;
        $rc;
        $lastIndex = 0;

        while (preg_match($tokenizer, $json, $tmp, PREG_OFFSET_CAPTURE, $lastIndex)) {
            $tmp = $tmp[0];
            $lastIndex = $tmp[1] + strlen($tmp[0]);
            $lc = substr($json, 0, $lastIndex - strlen($tmp[0]));
            $rc = substr($json, $lastIndex);
            if (!$in_multiline_comment && !$in_singleline_comment) {
                $tmp2 = substr($lc, $from);
                if (!$in_string) {
                    $tmp2 = preg_replace("/(\n|\r|\s)*/", '', $tmp2);
                }
                $new_str[] = $tmp2;
            }
            $from = $lastIndex;
            if ("\"" === $tmp[0] && !$in_multiline_comment && !$in_singleline_comment) {
                preg_match("/(\\\\)*$/", $lc, $tmp2);
                if (!$in_string || !$tmp2 || 0 === (strlen($tmp2[0]) % 2)) {
                    $in_string = !$in_string;
                }
                $from--;
                $rc = substr($json, $from);
            } elseif ('/*' === $tmp[0] && !$in_string && !$in_multiline_comment && !$in_singleline_comment) {
                $in_multiline_comment = true;
            } elseif ('*/' === $tmp[0] && !$in_string && $in_multiline_comment && !$in_singleline_comment) {
                $in_multiline_comment = false;
            } elseif ('//' === $tmp[0] && !$in_string && !$in_multiline_comment && !$in_singleline_comment) {
                $in_singleline_comment = true;
            } elseif (("\n" === $tmp[0] || "\r" === $tmp[0]) && !$in_string && !$in_multiline_comment && $in_singleline_comment) {
                $in_singleline_comment = false;
            } elseif (!$in_multiline_comment && !$in_singleline_comment && !(preg_match("/\n|\r|\s/", $tmp[0]))) {
                $new_str[] = $tmp[0];
            }
        }

        if (!isset($rc)) {
            $rc = $json;
        }

        $new_str[] = $rc;

        return implode('', $new_str);
    }
}
