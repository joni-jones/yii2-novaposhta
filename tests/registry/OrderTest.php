<?php
namespace jones\novaposhta\tests\registry;

use jones\novaposhta\components\Request;
use jones\novaposhta\components\RequestFactory;
use jones\novaposhta\registry\Order;
use Yii;

class OrderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \jones\novaposhta\registry\Order
     */
    private $order;

    /**
     * @var \jones\novaposhta\components\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    protected function setUp()
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['build', 'execute', '__wakeup'])
            ->getMock();

        $requestFactory = $this->getMockBuilder(RequestFactory::class)
            ->setMethods(['create'])
            ->getMock();
        $requestFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->request);

        $this->order = Yii::createObject(Order::class, [$requestFactory]);
    }

    /**
     * @covers \jones\novaposhta\registry\Order::validate()
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
     * @covers \jones\novaposhta\registry\Order::validate()
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
     * @covers \jones\novaposhta\registry\Order::validate()
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
            ->method('build')
            ->willReturnSelf();

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
     * @covers \jones\novaposhta\registry\Order::delete()
     */
    public function testDelete()
    {
        $np_id = '20151111213435';
        $this->request->expects(static::once())
            ->method('build')
            ->willReturnSelf();
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'close' => Order::STATUS_DELETED
            ]);
        static::assertTrue($this->order->delete($np_id));
    }
}
