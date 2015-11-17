<?php
namespace jones\novaposhta\models;

use jones\novaposhta\delivery\Estimation;
use jones\novaposhta\helpers\Formatter;
use Yii;
use yii\base\Model;

/**
 * Class CountPrice
 * @package jones\novaposhta\delivery
 *
 * @property string $senderCity
 * @property string $recipientCity
 * @property int $mass
 * @property int $height
 * @property int $width
 * @property int $depth
 * @property float $publicPrice
 * @property int $deliveryType_id
 * @property int $loadType_id
 * @property float $postpay_sum
 * @property int $floor_count
 * @property string $date
 */
class CountPrice extends Model
{
    use Formatter;

    /**
     * @var string
     */
    public $senderCity;

    /**
     * @var string
     */
    public $recipientCity;

    /**
     * @var int
     */
    public $mass;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $depth;

    /**
     * @var double
     */
    public $publicPrice;

    /**
     * @var int
     */
    public $deliveryType_id;

    /**
     * @var int
     */
    public $loadType_id;

    /**
     * @var double
     */
    public $postpay_sum;

    /**
     * @var int
     */
    public $floor_count;

    /**
     * @var string
     */
    public $date;

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
            [
                ['mass', 'height', 'width', 'depth', 'deliveryType_id', 'loadType_id', 'floor_count'],
                'integer',
                'min' => 0
            ],
            [['senderCity', 'recipientCity'], 'string'],
            [['publicPrice', 'postpay_sum'], 'double', 'min' => 0],
            [
                [
                    'senderCity', 'recipientCity', 'mass', 'height', 'width', 'depth', 'publicPrice',
                    'date'
                ],
                'required'
            ],
            ['deliveryType_id', 'default', 'value' => Estimation::DELIVERY_TYPE_DEPARTMENT_TO_DEPARTMENT],
            ['loadType_id', 'default', 'value' => Estimation::LOAD_TYPE_CARGO],
            ['deliveryType_id', 'in', 'range' => array_keys(Estimation::getDeliveryTypeList())],
            ['loadType_id', 'in', 'range' => array_keys(Estimation::getLoadTypeList())],
            [['date'], 'date', 'format' => 'dd.MM.yyyy'],
            [['publicPrice', 'postpay_sum'], 'convertAmount'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'senderCity' => Yii::t('api', 'Sender city'),
            'recipientCity' => Yii::t('api', 'Recipient city'),
            'mass' => Yii::t('api', 'Mass'),
            'height' => Yii::t('api', 'Height'),
            'width' => Yii::t('api', 'Width'),
            'depth' => Yii::t('api', 'Depth'),
            'publicPrice' => Yii::t('api', 'Public price'),
            'deliveryType_id' => Yii::t('api', 'Delivery type'),
            'loadType_id' => Yii::t('api', 'Load type'),
            'postpay_sum' => Yii::t('api', 'Postpay sum'),
            'floor_count' => Yii::t('api', 'Floor count'),
            'date' => Yii::t('api', 'Date'),
        ];
    }

    /**
     * Convert amount
     * @param $attribute
     */
    public function convertAmount($attribute)
    {
        $this->$attribute = $this->formatPrice($this->$attribute);
    }
}