<?php

declare(strict_types=1);

namespace Src\Core\Additional;

use DB;

/**
 * Class EmailSender
 * @package Src\CustomHelpers
 */
class EmailSender
{
    /**
     * @var object|null
     */
    protected null|object $templateData;

    /**
     * EmailSender constructor.
     * @param int $type
     * @param array $params
     */
    public function __construct(protected int $type, protected array $params)
    {
        $this->setTemplateData();
    }

    /**
     *
     */
    protected function setTemplateData(): void
    {
        $this->templateData = DB::table('email_templates')->where('type', '=', $this->type)->first();
        $params = $this->params;
        $this->format($params);
    }

    /**
     * @param array $params
     */
    protected function format(array $params): void
    {
        foreach ($params as $name => $value) {
            $this->templateData->body = str_ireplace('[' . $name . ']', (string)$value, $this->templateData->body);
        }
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->templateData->body;
    }
}
