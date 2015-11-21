<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Api;
use jones\novaposhta\components\RequestFactory;
use SimpleXMLElement;
use Yii;

/**
 * Class ApiTest
 * @package jones\novaposhta\tests
 */
class ApiTest extends TestCase
{
    /**
     * @var \jones\novaposhta\Api
     */
    private $api;

    protected function setUp()
    {
        $this->api = Yii::createObject(Api::class);
    }

    /**
     * @covers \jones\novaposhta\Api::__construct
     */
    public function testApiConstructor()
    {
        $this->invokeConstructor(Api::class, [Yii::createObject(RequestFactory::class)]);
    }

    /**
     * @covers \jones\novaposhta\Api::validate
     * @throws \yii\base\NotSupportedException
     * @expectedException \yii\base\NotSupportedException
     * @expectedExceptionMessage This method should be implemented in children classes
     */
    public function testValidate()
    {
        $this->api->validate([]);
    }

    /**
     * @covers \jones\novaposhta\Api::addError
     */
    public function testAddError()
    {
        $this->api->addError('Test error message');
        static::assertEquals(1, sizeof($this->api->getErrors()));
    }

    /**
     * @covers \jones\novaposhta\Api::addErrors
     */
    public function testAddErrorsMultiple()
    {
        $message1 = '"Mass" should be not empty';
        $message2 = '"Mass" should be positive';
        $errors = [
            'mass' => [
                $message1, $message2
            ]
        ];
        $expected = [$message1, $message2];
        $this->api->addErrors($errors);
        static::assertEquals($expected, $this->api->getErrors());
    }

    /**
     * @covers \jones\novaposhta\Api::addErrors
     */
    public function testAddErrorsSingle()
    {
        $errors = ['"Order Id" should not be empty'];
        $this->api->addErrors($errors);
        $actual = $this->api->getErrors();
        static::assertEquals(1, sizeof($actual));
        static::assertEquals($errors, $actual);
    }

    /**
     * @covers \jones\novaposhta\Api::getErrors
     */
    public function testGetErrors()
    {
        static::assertEmpty($this->api->getErrors());
        $this->api->addError('Api error message first');
        $this->api->addError('Api error message second');
        $errors = $this->api->getErrors();
        static::assertTrue(is_array($errors));
        static::assertEquals(2, sizeof($errors));
    }

    /**
     * @covers \jones\novaposhta\Api::createRequestFromArray
     */
    public function testCreateRequestFromArray()
    {
        $order_id = '120023';
        $description = 'Circle';
        $params = [
            'order_id' => $order_id,
            'sender_city' => 'Kiev',
            'pack_type' => 'box',
            'details' => [
                'description' => $description
            ]
        ];
        /** @var \SimpleXMLElement $request */
        $request = $this->api->createRequestFromArray($params, 'order');

        static::assertNotEmpty($request->order);
        static::assertEquals($order_id, (string) $request->order['order_id']);
        static::assertEquals($description, (string) $request->order->details['description']);
    }

    /**
     * @covers \jones\novaposhta\Api::createRequest
     */
    public function testCreateRequest()
    {
        $orderId = '342323432';
        $request = $this->api->createRequest($orderId, 'order');
        static::assertNotEmpty($request->order);
        static::assertEquals($orderId, (string) $request->order);
    }

    /**
     * @covers \jones\novaposhta\Api::createDocument
     */
    public function testCreateDocument()
    {
        $error = 'Order id should not be empty';
        $request = $this->api->createRequest($error, 'error');
        static::assertEquals($error, (string) $request->error);
    }

    /**
     * @covers \jones\novaposhta\Api::appendAttributes
     */
    public function testAppendAttributes()
    {
        $params = [
            'order_id' => '201511211056',
            'details' => [
                'description' => 'Order description',
                'comments' => [
                    ['sender' => 'Glass']
                ]
            ]
        ];

        $request = $this->api->createRequestFromArray($params, 'order');
        static::assertInstanceOf(SimpleXMLElement::class, $request);
        static::assertInstanceOf(SimpleXMLElement::class, $request->order->details);
        static::assertInstanceOf(SimpleXMLElement::class, $request->order->details->comments);
        static::assertInstanceOf(SimpleXMLElement::class, $request->order->details->comments->sender);
    }
}
