<?php
namespace Ipol\Demo\Api\Entity\Response\Part\OrdersMake;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class ConflictsInfoList
 * @package Ipol\Demo\Api
 * @subpackage Response
 * @method ConflictsInfo getFirst
 * @method ConflictsInfo getNext
 * @method ConflictsInfo getLast
 */
class ConflictsInfoList extends AbstractCollection
{
    protected $ConflictsInfos;

    public function __construct()
    {
        parent::__construct('ConflictsInfos');
    }
}