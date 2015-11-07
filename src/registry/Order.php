<?php
namespace jones\novaposhta\registry;

use Yii;
use jones\novaposhta\Api;

/**
 * Class Order
 * @package jones\novaposhta\registry
 */
class Order extends Api
{
    const PAY_TYPE_SPOT = 1;

    const PAY_TYPE_CASHLESS = 2;

    const PAYER_RECEIVER = 0;

    const PAYER_SENDER = 1;

    const PAYER_OTHER = 2;

    const SATURDAY_DELIVERY_DISABLED = 0;

    const SATURDAY_DELIVERY_ENABLED = 1;

    const REDELIVERY_TYPE_DOCUMENT = 1;

    const REDELIVERY_TYPE_MONEY = 2;

    const REDELIVERY_TYPE_PALLET = 3;

    const REDELIVERY_TYPE_PRODUCT = 4;

    const REDELIVERY_TYPE_OTHER = 5;

    const REDELIVERY_TYPE_EXPRESS_CONSIGNMENT_CARRIER = 6;

    const REDELIVERY_PAYER_SENDER = 1;

    const REDELIVERY_PAYER_RECEIVER = 2;

    /**
     * Get list of available pay types
     * @static
     * @return array
     */
    public static function getPayTypes()
    {
        return [
            self::PAY_TYPE_SPOT => Yii::t('api', 'Spot'),
            self::PAY_TYPE_CASHLESS => Yii::t('api', 'Cashless')
        ];
    }

    /**
     * Get list of available payer types
     * @static
     * @return array
     */
    public static function getPayerTypes()
    {
        return [
            self::PAYER_RECEIVER => Yii::t('api', 'Receiver'),
            self::PAYER_SENDER => Yii::t('api', 'Sender'),
            self::PAYER_OTHER => Yii::t('api', 'Third person')
        ];
    }

    /**
     * Get options for saturday delivery
     * @static
     * @return array
     */
    public static function getSaturdayDeliveryTypes()
    {
        return [
            self::SATURDAY_DELIVERY_DISABLED => Yii::t('api', 'Disabled'),
            self::SATURDAY_DELIVERY_ENABLED => Yii::t('api', 'Enabled')
        ];
    }

    /**
     * Return list of available redelivery types
     * @static
     * @return array
     */
    public static function getRedeliveryTypes()
    {
        return [
            self::REDELIVERY_TYPE_DOCUMENT => Yii::t('api', 'Documents'),
            self::REDELIVERY_TYPE_MONEY => Yii::t('api', 'Money'),
            self::REDELIVERY_TYPE_PALLET => Yii::t('api', 'Pallets'),
            self::REDELIVERY_TYPE_PRODUCT => Yii::t('api', 'Products'),
            self::REDELIVERY_TYPE_OTHER => Yii::t('api', 'Other'),
            self::REDELIVERY_TYPE_EXPRESS_CONSIGNMENT_CARRIER => Yii::t('api', 'Express consignment carrier'),
        ];
    }

    /**
     * Return list of available redelivery payer types
     * @access
     * @return array
     */
    public static function getRedeliveryPayerTypes()
    {
        return [
            self::REDELIVERY_PAYER_SENDER => Yii::t('api', 'Sender'),
            self::REDELIVERY_PAYER_RECEIVER => Yii::t('api', 'Receiver')
        ];
    }
}
