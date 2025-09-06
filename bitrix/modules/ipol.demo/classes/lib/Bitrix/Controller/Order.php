<?php
namespace Ipol\Demo\Bitrix\Controller;

use Ipol\Demo\Admin\BitrixLoggerController;
use Ipol\Demo\Bitrix\Entity\BasicResponse;
use Ipol\Demo\Core\Order\OrderCollection;

class Order extends AbstractController
{
    /**
     * @var \Ipol\Demo\Core\Order\Order
     */
    protected $order;

    public function __construct($module_id, $module_lbl, $order = null)
    {
        parent::__construct($module_id, $module_lbl);

        $this->logger =
            ($this->options->fetchDebug() === 'Y' && $this->options->fetchOption('debug_order') === 'Y') ?
                new BitrixLoggerController() :
                false;

        $this->application->setLogger($this->logger);

        $this->order = $order;
    }

    /**
     * @return BasicResponse
     */
    public function send()
    {
        $orderCollection = new OrderCollection();
        $orderCollection->add($this->order);

        $result = new BasicResponse();

        $answer = $this->application->ordersMake($orderCollection);
        if ($answer->isSuccess()) {
            $result->setSuccess(true)->setData($answer);
        } else {
            $errors = [];
            if ($this->application->getErrorCollection()) {
                $this->application->getErrorCollection()->reset();
                while ($error = $this->application->getErrorCollection()->getNext()) {
                    $errors[] = $error->getMessage();
                }
            } else {
                $errors[] = 'Error while sending order, but no error messages get from application.';
            }

            $result->setSuccess(false)->setErrorText(implode("\n", $errors));
        }

        return $result;
    }

    public function delete()
    {
        $obReturn = new BasicResponse();

        try {
            ob_start();
            $obResponse = $this->application->cancelOrderByNumber($this->order->getNumber());
            ob_get_clean();
            if($obResponse && $obResponse->isSuccess()){
                $obResponse->setSuccess(true);
            } else {
                $obReturn
                    ->setSuccess(false)
                    ->setErrorText(
                        $this->application->getErrorCollection()->getLast() ?
                            $this->application->getErrorCollection()->getLast()->getMessage() :
                            '');
            }
        }catch(\Exception $e){
            $obReturn
                ->setSuccess(false)
                ->setErrorText(
                    $this->application->getErrorCollection()->getLast() ?
                        $this->application->getErrorCollection()->getLast()->getMessage() :
                        '');
        }

        return $obReturn;
    }

    public function generateBarcode($uniqueItemCode){
        $obReturn = $this->application->generateBarcode($uniqueItemCode);

        if($obReturn->isSuccess()){
            return $obReturn->getBarcode();
        }

        return false;
    }

    /**
     * @return \Ipol\Demo\Core\Order\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     * @return $this
     */
    public function setOrder(\Ipol\Demo\Core\Order\Order $order)
    {
        $this->order = $order;

        return $this;
    }
}