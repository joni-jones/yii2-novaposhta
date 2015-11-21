<?php
namespace jones\novaposhta\tests\registry;

use jones\novaposhta\registry\Order;
use jones\novaposhta\tests\TestCase;
use Yii;

/**
 * Class OrderTest
 * @package jones\novaposhta\tests\registry
 */
class OrderTest extends TestCase
{
    /**
     * @var \jones\novaposhta\registry\Order
     */
    private $order;

    protected function setUp()
    {
        $requestFactory = $this->getRequestFactory();
        $this->order = Yii::createObject(Order::class, [$requestFactory]);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validate
     */
    public function testValidateEmptyParams()
    {
        static::assertFalse($this->order->validate([]));
        static::assertEquals('Request params should not be empty', $this->order->getErrors()[0]);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validate
     */
    public function testFailedValidate()
    {
        $params = [
            'redelivery_type' => Order::REDELIVERY_TYPE_PALLET
        ];
        static::assertFalse($this->order->validate($params));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validate
     */
    public function testValidate()
    {
        static::assertTrue($this->order->validate(['order_id' => '2432432423']));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validateRedeliveryType
     */
    public function testValidateEmptyRedeliveryPayer()
    {
        $message = 'Redelivery payer should be specified';
        static::assertFalse($this->order->validate([
            'redelivery_type' => Order::REDELIVERY_TYPE_PALLET
        ]));
        static::assertEquals($message, $this->order->getErrors()[0]);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validateRedeliveryType
     */
    public function testValidateIncorrectRedeliveryPayer()
    {
        $message = 'Unsupported redelivery payer for current redelivery type';
        static::assertFalse($this->order->validate([
            'redelivery_type' => Order::REDELIVERY_TYPE_EXPRESS_CONSIGNMENT_CARRIER,
            'redelivery_payment_payer' => Order::REDELIVERY_PAYER_RECEIVER
        ]));
        static::assertEquals($message, $this->order->getErrors()[0]);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validateRedeliveryType
     */
    public function testValidatePalletRedelivery()
    {
        $message = 'Invalid payer type for pallets redelivery';
        static::assertFalse($this->order->validate([
            'redelivery_type' => Order::REDELIVERY_TYPE_PALLET,
            'redelivery_payment_payer' => Order::REDELIVERY_PAYER_RECEIVER
        ]));
        static::assertEquals($message, $this->order->getErrors()[0]);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validateRedeliveryType
     */
    public function testValidateRedeliveryType()
    {
        $params = [
            'redelivery_type' => Order::REDELIVERY_TYPE_EXPRESS_CONSIGNMENT_CARRIER,
            'redelivery_payment_payer' => Order::REDELIVERY_PAYER_SENDER
        ];
        static::assertTrue($this->order->validate($params));

        $params['redelivery_type'] = Order::REDELIVERY_TYPE_PALLET;
        $params['redelivery_payment_payer'] = Order::REDELIVERY_PAYER_SENDER;
        static::assertTrue($this->order->validate($params));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::create
     */
    public function testCreate()
    {
        $order_id = '120023';
        $np_id = '59000111640001';
        $params = [
            'order_id' => $order_id,
            'sender_city' => 'Kiev',
            'sender_company' => 'No Name',
            'sender_address' => 1,
            'sender_contact' => 'Vladimir',
            'sender_phone' => '0960000001',
            'rcpt_city_name' => 'Vinnitsya',
            'rcpt_name' => 'Person',
            'rcpt_warehouse' => 5,
            'rcpt_contact' => 'Ivan',
            'rcpt_phone_num' => '0980000001',
            'pack_type' => 'box',
            'description' => 'shoes',
            'pay_type' => 1,
            'payer' => 1,
            'cost' => 200,
            'weight' => '0,1',
            'order_cont' => [
                'cont_description' => 'Circle'
            ]
        ];

        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'order_id' => $order_id,
                'np_id' => $np_id
            ]);
        $result = $this->order->create($params);
        static::assertEquals($np_id, $result);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::create
     */
    public function testCreateWithFailedValidation()
    {
        $this->request->expects(static::never())
            ->method('build');
        $result = $this->order->create([]);
        static::assertFalse($result);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::delete
     */
    public function testDelete()
    {
        $np_id = '20151111213435';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'close' => Order::STATUS_DELETED
            ]);
        static::assertTrue($this->order->delete($np_id));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::getPayTypes
     */
    public function testGetPayTypes()
    {
        $expected = [1, 2];
        static::assertEquals($expected, array_keys(Order::getPayTypes()));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::getPayerTypes
     */
    public function testGetPayerTypes()
    {
        $expected = [Order::PAYER_RECEIVER, Order::PAYER_SENDER, Order::PAYER_OTHER];
        static::assertEquals($expected, array_keys(Order::getPayerTypes()));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::getSaturdayDeliveryTypes
     */
    public function testGetSaturdayDeliveryTypes()
    {
        $expected = [0, 1];
        static::assertEquals($expected, array_keys(Order::getSaturdayDeliveryTypes()));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::getRedeliveryTypes
     */
    public function testRedeliveryTypes()
    {
        $expected = [1, 2, 3, 4, 5, 6];
        static::assertEquals($expected, array_keys(Order::getRedeliveryTypes()));
    }

    /**
     * @covers \jones\novaposhta\registry\Order::getRedeliveryPayerTypes
     */
    public function testRedeliveryPayerTypes()
    {
        $expected = [1, 2];
        static::assertEquals($expected, array_keys(Order::getRedeliveryPayerTypes()));
    }
}
