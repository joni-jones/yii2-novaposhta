<?php
namespace jones\novaposhta\tests\models;

use jones\novaposhta\models\CountPrice;
use Yii;

class CountPriceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \jones\novaposhta\models\CountPrice
     */
    private $model;

    protected function setUp()
    {
        $this->model = Yii::createObject(CountPrice::class);
    }

    /**
     * @covers \jones\novaposhta\models\CountPrice::attributeLabels
     */
    public function testGetAttributeLabels()
    {
        static::assertEquals(
            array_keys($this->model->attributes),
            array_keys($this->model->attributeLabels())
        );
    }

    /**
     * @covers \jones\novaposhta\models\CountPrice::convertAmount
     */
    public function testConvertAmount()
    {
        $attribute = 'publicPrice';
        $this->model->$attribute = 23.05;
        $this->model->convertAmount($attribute);
        static::assertEquals('23,05', $this->model->$attribute);
    }
}

 