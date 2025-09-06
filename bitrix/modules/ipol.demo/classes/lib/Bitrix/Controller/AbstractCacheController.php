<?php
namespace Ipol\Demo\Bitrix\Controller;

use Ipol\Demo\Bitrix\Entity\Cache;

/**
 * Class AbstractCacheController
 * @package Ipol\Demo\
 */
class AbstractCacheController extends AbstractController
{
    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return Cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param Cache $cache
     * @return $this
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    public function toCache($wat, $hash=false)
    {
        $hash = ($hash) ? $hash : $this->getHash();

        if($wat && $this->getCache())
        {
            $this->getCache()->setCache($hash,$wat);
        }

        return $this;
    }
}