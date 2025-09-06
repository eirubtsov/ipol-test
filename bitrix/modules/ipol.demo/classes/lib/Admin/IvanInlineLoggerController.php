<?php


namespace Ipol\Demo\Admin;


use Ipol\Demo\Api\Logger\InlineRoute;
use Ipol\Demo\Api\Logger\Psr\Log\LogLevel;

/**
 * Class IvanInlineLoggerController
 * @package Ipol\Demo\Admin
 */
class IvanInlineLoggerController extends \Ipol\Demo\Api\Logger\Logger
{
    /**
     * @var string
     */
    protected $curlTemplate = '{method}' . ' ' . '{process}' . PHP_EOL . '{content}';

    /**
     * BitrixLoggerController constructor.
     */
    public function __construct()
    {
        $route = new InlineRoute();
        $route->enable();
        parent::__construct([$route]);
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message = '', array $context = []): void
    {
        if ($level === LogLevel::DEBUG && array_key_exists('method', $context)) {
            parent::log($level, $this->interpolate($this->curlTemplate, $context), []);
        } else
            parent::log($level, $message, $context);
    }
}