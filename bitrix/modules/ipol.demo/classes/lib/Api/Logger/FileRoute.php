<?php
namespace Ipol\Demo\Api\Logger;

/**
 * Class FileRoute
 * @package Ipol\Demo\Api
 * @subpackage Logger
 */
class FileRoute extends Route
{
    /**
     * @var string path to file
     */
    public $filePath;

    /**
     * FileRoute constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @param string $dataString
     */
    public function log(string $dataString): void
    {
        if (!file_exists(dirname($this->filePath))) {
           mkdir(dirname($this->filePath), 0777, 1);
        }

        file_put_contents($this->filePath,
            trim($dataString) . PHP_EOL . '--------------------------------------------' . PHP_EOL,
            FILE_APPEND);
    }

    public function read(): string
    {
        if (file_exists($this->filePath)) {
            return (string) file_get_contents($this->filePath);
        } else {
            return '';
        }
    }
}