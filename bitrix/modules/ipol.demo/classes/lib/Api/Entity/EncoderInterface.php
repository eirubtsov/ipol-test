<?php

namespace Ipol\Demo\Api\Entity;

/**
 * Interface EncoderInterface
 * @package Ipol\Demo\Others
 * Encodes handle from API-server into cms encoding
 */
interface EncoderInterface
{
    public function encodeToAPI($handle);

    public function encodeFromAPI($handle);
}