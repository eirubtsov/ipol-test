<?php
namespace Ipol\Demo\Bitrix\Entity;

use Ipol\Demo\Bitrix\Tools;
use Ipol\Demo\Api\Entity\EncoderInterface;

/**
 * Class Encoder
 * @package Ipol\Demo\
 * Encodes handle from API-server into cms encoding
 */
class Encoder implements EncoderInterface
{
    public function encodeFromAPI($handle)
    {
        return Tools::encodeFromUTF8($handle);
    }

    public function encodeToAPI($handle)
    {
        return Tools::encodeToUTF8($handle);
    }
}