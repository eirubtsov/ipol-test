<?php
namespace Ipol\Demo\Api\Entity\Response\Part\OrdersMake;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class ContentList
 * @package Ipol\Demo\Api
 * @subpackage Response
 * @method Content getFirst
 * @method Content getNext
 * @method Content getLast
 */
class ContentList extends AbstractCollection
{
    protected $Contents;

    public function __construct()
    {
        parent::__construct('Contents');
    }
}