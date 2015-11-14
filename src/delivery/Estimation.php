<?php
namespace jones\novaposhta\delivery;

use jones\novaposhta\Api;
use jones\novaposhta\models\CountPrice;
use Yii;

/**
 * Class Estimation
 * @package jones\novaposhta\delivery
 */
class Estimation extends Api
{
    const DELIVERY_TYPE_ADDRESS_TO_ADDRESS = 1;

    const DELIVERY_TYPE_ADDRESS_TO_DEPARTMENT = 2;

    const DELIVERY_TYPE_DEPARTMENT_TO_ADDRESS = 3;

    const DELIVERY_TYPE_DEPARTMENT_TO_DEPARTMENT = 4;

    const LOAD_TYPE_CARGO = 1;

    const LOAD_TYPE_DOCUMENT = 4;

    /**
     * Get list of available delivery types
     * @static
     * @return array
     */
    public static function getDeliveryTypeList()
    {
        return [
            self::DELIVERY_TYPE_ADDRESS_TO_ADDRESS => Yii::t('api', 'Address-Address'),
            self::DELIVERY_TYPE_ADDRESS_TO_DEPARTMENT => Yii::t('api', 'Address-Department'),
            self::DELIVERY_TYPE_DEPARTMENT_TO_ADDRESS => Yii::t('api', 'Department-Address'),
            self::DELIVERY_TYPE_DEPARTMENT_TO_DEPARTMENT => Yii::t('api', 'Department-Department'),
        ];
    }

    /**
     * Get list of available load types
     * @static
     * @return array
     */
    public static function getLoadTypeList()
    {
        return [
            self::LOAD_TYPE_CARGO => Yii::t('api', 'Cargo'),
            self::LOAD_TYPE_DOCUMENT => Yii::t('api', 'Valuable Document'),
        ];
    }

    /**
     * Calculate delivery estimated date
     * @param $senderCity
     * @param $recipientCity
     * @param $date 'DD.MM.YYYY' format
     * @param int $type
     * @param bool $satDelivery
     * @return string
     */
    public function getEstimatedDate(
        $senderCity,
        $recipientCity,
        $date,
        $type = self::DELIVERY_TYPE_DEPARTMENT_TO_DEPARTMENT,
        $satDelivery = false
    ) {
        $params = [
            'senderCity' => $senderCity,
            'recipientCity' => $recipientCity,
            'date' => $date, 'dd.MM.yyyy',
            'deliveryTypeId' => $type,
            'satDelivery' => (int) $satDelivery
        ];
        $document = $this->createRequestFromArray($params, 'getEstimatedDeliveryDate');
        $response = $this->execute($document);
        return $response['estimatedDeliveryDate'];
    }

    /**
     * Calculate delivery cost and date
     * @param CountPrice $data
     * @return array|bool
     */
    public function getPriceWithDate(CountPrice $data)
    {
        if (!$data->validate()) {
            $this->addErrors($data->getErrors());
            return false;
        }
        $document = $this->createRequestFromArray($data->getAttributes(), 'countPrice');
        $response = $this->execute($document);
        if (!empty($response['error'])) {
            $this->addError($response['error']);
            return false;
        }
        return $response;
    }
}