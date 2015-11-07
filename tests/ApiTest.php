<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Api;
use Yii;

/**
 * Class ApiTest
 * @package jones\novaposhta\tests
 */
class ApiTest extends \PHPUnit_Framework_TestCase
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
     * @covers \jones\novaposhta\Api::validate()
     * @throws \yii\base\NotSupportedException
     * @expectedException \yii\base\NotSupportedException
     * @expectedExceptionMessage This method should be implemented in children classes
     */
    public function testValidate()
    {
        $this->api->validate();
    }

    /**
     * @covers \jones\novaposhta\Api::createRequestFromArray()
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

        static::assertTrue($request instanceof \SimpleXMLElement);
        static::assertTrue(!empty($request->order));
        static::assertEquals($order_id, (string) $request->order['order_id']);
        static::assertTrue($request->order->details instanceof \SimpleXMLElement);
        static::assertEquals($description, (string) $request->order->details['description']);
    }
}
