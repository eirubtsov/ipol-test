<?php
namespace Ipol\Demo\Api\Entity\UniversalPart;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class WorkingTimeList
 * @package Ipol\Demo\Api
 * @subpackage Entity\UniversalPart
 * @method WorkingTime getFirst
 * @method WorkingTime getNext
 * @method WorkingTime getLast
 */
class WorkingTimeList extends AbstractCollection
{
    protected $WorkingTimes;

    public function __construct()
    {
        parent::__construct('WorkingTimes');
    }
}