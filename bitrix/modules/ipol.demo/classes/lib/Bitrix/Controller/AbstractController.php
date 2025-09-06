<?php
namespace Ipol\Demo\Bitrix\Controller;

use \Ipol\Demo\Admin\Logger;
use \Ipol\Demo\Bitrix\Entity\Cache;
use \Ipol\Demo\Bitrix\Entity\Encoder;
use \Ipol\Demo\Bitrix\Entity\Options;
use \Ipol\Demo\Demo\DemoApplication;

/**
 * Class AbstractController
 * @package Ipol\Demo\Bitrix\Controller
 * Parent class for all controllers. Provide fields with entities for all controllers
 */
class AbstractController
{
    /**
     * @var Options module options entity
     */
    protected $options;

    /**
     * @var Encoder for text encoding operations
     */
    protected $encoder;

    /**
     * @var string hash for cache
     */
    protected $hash;

    /**
     * @var Cache entity for cache
     */
    protected $cache;

    /**
     * @var Logger entity for logs
     */
    protected $logger;

    /**
     * @var DemoApplication entity for API calls
     */
    protected $application;

    protected static $MODULE_ID;
    protected static $MODULE_LBL;

    public function __construct($module_id,$module_lbl)
    {
        self::$MODULE_ID  = $module_id;
        self::$MODULE_LBL = $module_lbl;

        $this->options = new Options();
        $this->encoder = new Encoder();
        $this->cache   = new Cache();

        $this->application = new DemoApplication(
            $this->getOptions()->fetchApiKey(),
            ($this->getOptions()->fetchIsTest() === 'Y'),
            $this->getOptions()->fetchTimeout(),
            $this->encoder,
            $this->cache
        );

        $this->application->setOptions($this->options);
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param Options $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }
}