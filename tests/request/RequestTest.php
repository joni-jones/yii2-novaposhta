<?php
namespace jones\novaposhta\tests\request;

use jones\novaposhta\converters\JsonConverter;
use jones\novaposhta\converters\XmlConverter;
use jones\novaposhta\http\Client;
use jones\novaposhta\http\ClientFactory;
use jones\novaposhta\request\Request;
use jones\novaposhta\tests\TestCase;
use SimpleXMLElement;
use Yii;

/**
 * Class RequestTest
 * @package jones\novaposhta\tests\components
 */
class RequestTest extends TestCase
{
    /**
     * @var \jones\novaposhta\converters\XmlConverter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $converter;

    /**
     * @var \jones\novaposhta\http\Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClient;

    /**
     * @var \jones\novaposhta\http\ClientFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClientFactory;

    protected function setUp()
    {
        $this->initConverterMock();
        $this->initHttpClientFactoryMock();

        $this->request = Yii::createObject(Request::class, [
            new XmlConverter(),
            $this->httpClientFactory,
            self::API_KEY,
        ]);
    }

    /**
     * @covers \jones\novaposhta\request\Request::__construct
     */
    public function testRequestConstructor()
    {
        $this->invokeConstructor(Request::class, [
            Yii::createObject(XmlConverter::class),
            Yii::createObject(ClientFactory::class),
            self::API_KEY,
        ]);
    }

    /**
     * @covers \jones\novaposhta\request\Request::build
     */
    public function testBuild()
    {
        $expected = $this->xmlToDomElement(file_get_contents(__DIR__ . '/../data/request.xml'));

        $params = [
            'FindByString' => 'Kiev',
        ];

        $this->request->build('Address', 'getCities', $params);
        $actual = $this->xmlToDomElement($this->request->getBody());
        static::assertEqualXMLStructure($expected, $actual, true);
    }

    /**
     * @covers \jones\novaposhta\request\Request::build
     */
    public function testBuildWithoutFilter()
    {
        $expected = $this->xmlToDomElement(file_get_contents(__DIR__ . '/../data/request_without_filter.xml'));

        $this->request->build('Address', 'getCities', []);
        $actual = $this->xmlToDomElement($this->request->getBody());
        static::assertEqualXMLStructure($expected, $actual, true);
    }

    /**
     * @covers \jones\novaposhta\request\Request::execute
     */
    public function testExecute()
    {
        $this->httpClient->expects(static::once())
            ->method('execute')
            ->willReturn(file_get_contents(__DIR__ . '/../data/response.xml'));

        $response = $this->request->execute();
        static::assertTrue(is_array($response));
        static::assertNotEmpty($response['success']);
        static::assertNotEmpty($response['data']);
        static::assertEquals(2, count($response['data']));
        static::assertNotEmpty($response['data'][0]['Description']);
        static::assertTrue(isset($response['errors']));
        static::assertTrue(isset($response['warnings']));
        static::assertTrue(isset($response['info']));
    }

    /**
     * @covers \jones\novaposhta\request\Request::getBody
     */
    public function testGetEmptyBody()
    {
        static::assertEmpty($this->request->getBody());
    }

    /**
     * @covers \jones\novaposhta\request\Request::getBody
     */
    public function testGetBody()
    {
        $expected = $this->xmlToDomElement(file_get_contents(__DIR__ . '/../data/request.xml'));

        $params = [
            'FindByString' => 'Kiev',
        ];

        $this->request->build('Address', 'getCities', $params);
        $actual = $this->xmlToDomElement($this->request->getBody());
        static::assertEqualXMLStructure($expected, $actual, true);
    }

    /**
     * @covers \jones\novaposhta\request\Request::getUrl
     */
    public function testGetXmlUrl()
    {
        $this->httpClient->expects(static::once())
            ->method('execute')
            ->willReturn(file_get_contents(__DIR__ . '/../data/response.xml'));

        $this->request->execute();
    }

    /**
     * @covers \jones\novaposhta\request\Request::getUrl
     */
    public function testGetJsonUrl()
    {
        $this->request = Yii::createObject(Request::class, [
            new JsonConverter(),
            $this->httpClientFactory,
            self::API_KEY,
        ]);
        $this->httpClient->expects(static::once())
            ->method('execute')
            ->willReturn(json_encode([
                'success' => 'true',
                'data' => [
                    ['Description' => 'Kiev'],
                ],
                'errors',
                'warnings',
                'info'
            ]));
        $this->request->execute();
    }

    /**
     * Convert xml string to DomElement
     * @param $xml
     * @return \DOMElement
     */
    private function xmlToDomElement($xml)
    {
        $document = new SimpleXMLElement($xml);
        $domElement = dom_import_simplexml($document);

        return $domElement;
    }

    /**
     * Create mock for converter factory
     */
    private function initConverterMock()
    {
        $this->converter = $this->getMockBuilder(XmlConverter::class)
            ->disableOriginalConstructor()
            ->setMethods(['encode', 'decode', 'getContentType', 'getType'])
            ->getMock();
    }

    /**
     * Create mock for http client factory
     */
    private function initHttpClientFactoryMock()
    {
        $this->httpClient = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMock();
        $this->httpClientFactory = $this->getMockBuilder(ClientFactory::class)
            ->setMethods(['create'])
            ->getMock();
        $this->httpClientFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->httpClient);
    }
}
