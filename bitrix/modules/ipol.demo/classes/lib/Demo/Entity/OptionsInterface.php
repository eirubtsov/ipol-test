<?php

namespace Ipol\Demo\Demo\Entity;

/**
 * Interface OptionsInterface
 * @package Ipol\Demo\Others
 * gets options - used "fetch",because "get" for getters
 * add specific options with "public function fetch<Option>" for overload
 */
interface OptionsInterface
{
    public static function fetchOption($handle);

    /**
     * @param $option
     * @param $handle
     * @return $this
     *
     * DOESN'T sets anything in cms options! Only in container-class!
     */
    public function pushOption($option, $handle);

}