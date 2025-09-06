<?php
namespace Ipol\Demo;

use \Bitrix\Main\ORM\Data\DataManager;
use \Bitrix\Main\ORM\Fields\BooleanField;
use \Bitrix\Main\ORM\Fields\DatetimeField;
use \Bitrix\Main\ORM\Fields\FloatField;
use \Bitrix\Main\ORM\Fields\IntegerField;
use \Bitrix\Main\ORM\Fields\StringField;
use \Bitrix\Main\ORM\Fields\TextField;
use \Bitrix\Main\ORM\Fields\ExpressionField;
use \Bitrix\Main\ORM\Fields\Validators\LengthValidator;

/**
 * Class OrdersTable
 * @package Ipol\Demo
 **/
class OrdersTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'ipol_demo_orders';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                ]
            ),
            new IntegerField(
                'BITRIX_ID',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'DEMO_ID',
                [
                    'validation' => [__CLASS__, 'validateDemoId'],
                ]
            ),
            new StringField(
                'DEMO_GUID',
                [
                    'validation' => [__CLASS__, 'validateDemoGuid'],
                ]
            ),
            new BooleanField(
                'BARK_GENERATE_BY_SERVER',
                [
                    'values' => array('N', 'Y'),
                    'default' => 'N',
                ]
            ),
            new StringField(
                'STATUS',
                [
                    'validation' => [__CLASS__, 'validateStatus'],
                ]
            ),
            new StringField(
                'DEMO_STATUS',
                [
                    'validation' => [__CLASS__, 'validateDemoStatus'],
                ]
            ),
            new StringField(
                'DEMO_EXECUTION_STATUS',
                [
                    'validation' => [__CLASS__, 'validateDemoExecutionStatus'],
                ]
            ),
            new TextField(
                'BRAND_NAME',
                [
                ]
            ),
            new TextField(
                'CLIENT_NAME',
                [
                    'required' => true,
                ]
            ),
            new TextField(
                'CLIENT_EMAIL',
                [
                ]
            ),
            new StringField(
                'CLIENT_PHONE',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateClientPhone'],
                ]
            ),
            new DatetimeField(
                'PLANNED_RECEIVE_DATE',
                [
                ]
            ),
            new DatetimeField(
                'SHIPMENT_DATE',
                [
                ]
            ),
            new StringField(
                'RECEIVER_LOCATION',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateReceiverLocation'],
                ]
            ),
            new TextField(
                'SENDER_LOCATION',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'UNDELIVERABLE_OPTION',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateUndeliverableOption'],
                ]
            ),
            new TextField(
                'CARGOES',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'CURRENCY',
                [
                    'validation' => [__CLASS__, 'validateCurrency'],
                ]
            ),
            new FloatField(
                'DELIVERY_COST',
                [
                ]
            ),
            new StringField(
                'DELIVERY_COST_CURRENCY',
                [
                    'validation' => [__CLASS__, 'validateDeliveryCostCurrency'],
                ]
            ),
            new FloatField(
                'PAYMENT_VALUE',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'PAYMENT_TYPE',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validatePaymentType'],
                ]
            ),
            new StringField(
                'PAYMENT_CURRENCY',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validatePaymentCurrency'],
                ]
            ),
            new FloatField(
                'PREPAYMENT',
                [
                ]
            ),
            new StringField(
                'PREPAYMENT_CURRENCY',
                [
                    'validation' => [__CLASS__, 'validatePrepaymentCurrency'],
                ]
            ),
            new FloatField(
                'PRICE',
                [
                    'required' => true,
                ]
            ),
            new StringField(
                'PRICE_CURRENCY',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validatePriceCurrency'],
                ]
            ),
            new TextField(
                'MESSAGE',
                [
                ]
            ),
            new StringField(
                'OK',
                [
                    'validation' => [__CLASS__, 'validateOk'],
                ]
            ),
            new StringField(
                'UPTIME',
                [
                    'validation' => [__CLASS__, 'validateUptime'],
                ]
            ),
        ];
    }

    /**
     * Returns validators for DEMO_ID field.
     *
     * @return array
     */
    public static function validateDemoId()
    {
        return [
            new LengthValidator(null, 14),
        ];
    }

    /**
     * Returns validators for DEMO_GUID field.
     *
     * @return array
     */
    public static function validateDemoGuid()
    {
        return [
            new LengthValidator(null, 36),
        ];
    }

    /**
     * Returns validators for STATUS field.
     *
     * @return array
     */
    public static function validateStatus()
    {
        return [
            new LengthValidator(null, 10),
        ];
    }

    /**
     * Returns validators for DEMO_STATUS field.
     *
     * @return array
     */
    public static function validateDemoStatus()
    {
        return [
            new LengthValidator(null, 11),
        ];
    }

    /**
     * Returns validators for DEMO_EXECUTION_STATUS field.
     *
     * @return array
     */
    public static function validateDemoExecutionStatus()
    {
        return [
            new LengthValidator(null, 50),
        ];
    }

    /**
     * Returns validators for CLIENT_PHONE field.
     *
     * @return array
     */
    public static function validateClientPhone()
    {
        return [
            new LengthValidator(null, 12),
        ];
    }

    /**
     * Returns validators for RECEIVER_LOCATION field.
     *
     * @return array
     */
    public static function validateReceiverLocation()
    {
        return [
            new LengthValidator(null, 36),
        ];
    }

    /**
     * Returns validators for UNDELIVERABLE_OPTION field.
     *
     * @return array
     */
    public static function validateUndeliverableOption()
    {
        return [
            new LengthValidator(null, 11),
        ];
    }

    /**
     * Returns validators for CURRENCY field.
     *
     * @return array
     */
    public static function validateCurrency()
    {
        return [
            new LengthValidator(null, 3),
        ];
    }

    /**
     * Returns validators for DELIVERY_COST_CURRENCY field.
     *
     * @return array
     */
    public static function validateDeliveryCostCurrency()
    {
        return [
            new LengthValidator(null, 3),
        ];
    }

    /**
     * Returns validators for PAYMENT_TYPE field.
     *
     * @return array
     */
    public static function validatePaymentType()
    {
        return [
            new LengthValidator(null, 10),
        ];
    }

    /**
     * Returns validators for PAYMENT_CURRENCY field.
     *
     * @return array
     */
    public static function validatePaymentCurrency()
    {
        return [
            new LengthValidator(null, 3),
        ];
    }

    /**
     * Returns validators for PREPAYMENT_CURRENCY field.
     *
     * @return array
     */
    public static function validatePrepaymentCurrency()
    {
        return [
            new LengthValidator(null, 3),
        ];
    }

    /**
     * Returns validators for PRICE_CURRENCY field.
     *
     * @return array
     */
    public static function validatePriceCurrency()
    {
        return [
            new LengthValidator(null, 3),
        ];
    }

    /**
     * Returns validators for OK field.
     *
     * @return array
     */
    public static function validateOk()
    {
        return [
            new LengthValidator(null, 1),
        ];
    }

    /**
     * Returns validators for UPTIME field.
     *
     * @return array
     */
    public static function validateUptime()
    {
        return [
            new LengthValidator(null, 10),
        ];
    }

    // Cool wrappers

    /**
     * Returns order data by order ID.
     *
     * @param int $id
     * @param array $select
     * @return array
     */
    public static function getByOrderId($id, $select = array())
    {
        return self::getList(array_filter(['select' => $select ?: null, 'filter' => ['=ID' => $id]]))->fetch();
    }

    /**
     * Returns order data by Bitrix id
     *
     * @param int $bitrixId
     * @param array $select
     * @return array
     */
    public static function getByBitrixId($bitrixId, $select = array())
    {
        return self::getList(array_filter(['select' => $select ?: null, 'filter' => ['=BITRIX_ID' => $bitrixId]]))->fetch();
    }

    /**
     * Returns order data by demo id
     *
     * @param int $demoId
     * @param array $select
     * @return array
     */
    public static function getByDemoId($demoId, $select = array())
    {
        return self::getList(array_filter(['select' => $select ?: null, 'filter' => ['=DEMO_ID' => $demoId]]))->fetch();
    }

    /**
     * Returns order data by demo Guid
     *
     * @param string $guid
     * @param array $select
     * @return array
     */
    public static function getByDemoGuid($guid, $select = array())
    {
        return self::getList(array_filter(['select' => $select ?: null, 'filter' => ['=DEMO_GUID' => $guid]]))->fetch();
    }

    /**
     * Return number of rows with some data
     *
     * @return int
     */
    public static function getDataCount()
    {
        $params = ['select' => ['CNT'], 'runtime' => [new ExpressionField('CNT', 'COUNT(*)')]];
        $result = self::getList($params)->fetch();
        return $result['CNT'];
    }
}