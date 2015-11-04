<?php
namespace jones\novaposhta\tests\components;

use jones\novaposhta\components\http\Client;
use jones\novaposhta\components\http\ClientFactory;
use jones\novaposhta\components\Request;
use Yii;

/**
 * Class RequestTest
 * @package jones\novaposhta\tests\components
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    const API_KEY = 'ei4ed5fM1';

    /**
     * @var \jones\novaposhta\components\Request
     */
    private $request;

    /**
     * @var \jones\novaposhta\components\http\Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $httpClient;

    protected function setUp()
    {
        $this->httpClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $httpClientFactory = $this->getMockBuilder(ClientFactory::class)
            ->setMethods(['create'])
            ->getMock();
        $httpClientFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->httpClient);

        $this->request = Yii::createObject(Request::class, [
            $httpClientFactory,
            ['api_key' => self::API_KEY]
        ]);
    }

    /**
     * @covers \jones\novaposhta\components\Request::build()
     */
    public function testBuild()
    {
        $document = simplexml_load_file(__DIR__.'/data/order_request.xml');
        $body = $document->asXML();
        $params = $this->getRequestDocument();
        $this->request->build($params);

        static::assertXmlStringEqualsXmlString($body, $this->request->getBody());
    }

    /**
     * @covers \jones\novaposhta\components\Request::build()
     */
    public function testBuildWithFilter()
    {
        $document = simplexml_load_file(__DIR__.'/data/order_request.xml');
        $document->addChild('filter', 'Kiev');
        $body = $document->asXML();

        $params = $this->getRequestDocument();

        $this->request->build($params, 'Kiev');

        static::assertXmlStringEqualsXmlString($body, $this->request->getBody());
    }

    /**
     * @covers \jones\novaposhta\components\Request::execute()
     */
    public function testExecute()
    {
        $this->httpClient->expects(static::once())
            ->method('execute')
            ->willReturn([
                'successCode' => Request::SUCCESS_STATUS
            ]);

        $response = $this->request->execute();
        static::assertTrue(is_array($response));
        static::assertArrayHasKey('successCode', $response);
    }

    /**
     * @covers \jones\novaposhta\components\Request::getBody()
     */
    public function testGetEmptyBody()
    {
        static::assertEmpty($this->request->getBody());
    }

    /**
     * @covers \jones\novaposhta\components\Request::getBody()
     */
    public function testGetBody()
    {
        $document = simplexml_load_file(__DIR__.'/data/default_request.xml');
        $body = $document->asXML();

        $params = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><file/>');
        $this->request->build($params);
        static::assertXmlStringEqualsXmlString($body, $this->request->getBody());
    }

    /**
     * Create xml document for request example
     * @return \SimpleXMLElement
     */
    private function getRequestDocument()
    {
        $params = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><file/>');
        $order = $params->addChild('order');
        $attributes = [
            'order_id' => '120023',
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
            'weight' => '0,1'
        ];
        foreach ($attributes as $key => $attr) {
            $order->addAttribute($key, $attr);
        }
        $cont = $order->addChild('order_cont');
        $cont->addAttribute('cont_description', 'Circle');
        return $params;
    }
}
 