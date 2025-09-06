<?php

namespace Ipol\Demo\Bitrix\Controller;


use Ipol\Demo\Admin\BitrixLoggerController;

class Status extends AbstractController
{
    public function __construct()
    {
        parent::__construct(IPOL_DEMO,IPOL_DEMO_LBL);

        $this->logger =
            ($this->options->fetchDebug() === 'Y' && $this->options->fetchOption('debug_status') === 'Y') ?
                new BitrixLoggerController() :
                false;

        $this->application->setLogger($this->logger);
    }

    public function checkStatus($bitrixNumber)
    {
        $req = $this->application->getOrderStatus(array($bitrixNumber),'senderOrderId');

        return $req;
    }

    public function checkStatuses($arBitrixNumbers)
    {
        $req = $this->application->getOrderStatus($arBitrixNumbers,'senderOrderId');

        return $req;
    }
}