<?php
namespace jones\novaposhta\tests\http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Response;
use jones\novaposhta\converters\ConverterInterface;
use jones\novaposhta\converters\XmlConverter;
use jones\novaposhta\http\Client;
use jones\novaposhta\request\Request;
use jones\novaposhta\tests\TestCase;
use Yii;

/**
 * Class ClientTest
 */
class ClientTest extends TestCase
{
    /**
     * @var \jones\novaposhta\http\Client
     */
    private $client;

    /**
     * @var \GuzzleHttp\Client|\PHPUnit_Framework_MockObject_MockObject
     */
    private $guzzleClient;

    /**
     * @var \GuzzleHttp\Psr7\Response|\PHPUnit_Framework_MockObject_MockObject
     */
    private $response;

    protected function setUp()
    {
        $this->response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMock();

        $this->guzzleClient = $this->getMockBuilder(GuzzleClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['post'])
            ->getMock();
        $this->guzzleClient->expects(static::any())
            ->method('post')
            ->willReturn($this->response);

        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMock();

        $this->client = Yii::createObject(Client::class, [
            $this->guzzleClient
        ]);
    }

    /**
     * @covers \jones\novaposhta\http\Client::__construct
     */
    public function testHttpClientConstructor()
    {
        $this->invokeConstructor(Client::class, [
            Yii::createObject(GuzzleClient::class)
        ]);
    }

    /**
     * @covers \jones\novaposhta\http\Client::execute
     */
    public function testExecute()
    {
        $this->request->expects(static::once())
            ->method('getBody')
            ->willReturn($this->getRequest());

        $this->response->expects(static::never())
            ->method('getRequest');
        $this->response->expects(static::never())
            ->method('hasResponse');
        $this->response->expects(static::never())
            ->method('getRequest');
        $this->response->expects(static::once())
            ->method('getBody')
            ->willReturn($this->getResponse());

        $response = $this->client->execute($this->request, ConverterInterface::FORMAT_JSON, Request::API_URL_JSON);
        $converter = new XmlConverter();
        $actual = $converter->decode($response);
        static::assertNotEmpty($actual['success']);
        static::assertTrue((boolean) $actual['success']);
    }

    /**
     * @covers \jones\novaposhta\http\Client::execute
     * @expectedException \jones\novaposhta\http\ClientException
     * @expectedExceptionMessage Exception thrown by Guzzle client
     */
    public function testExecuteWithClientException()
    {
        $message = 'Exception thrown by Guzzle client';
        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = $this->getMockBuilder(GuzzleRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exception = new ClientException($message, $request);

        $this->guzzleClient->expects(static::once())
            ->method('post')
            ->willThrowException($exception);

        $this->response->expects(static::never())
            ->method('getBody');

        $this->client->execute($this->request, ConverterInterface::FORMAT_JSON, Request::API_URL_JSON);
    }

    /**
     * @covers \jones\novaposhta\http\Client::execute
     * @expectedException \jones\novaposhta\http\ClientException
     * @expectedExceptionMessage Exception thrown by Guzzle client and has response
     */
    public function testExecuteWithClientExceptionAndHasResponse()
    {
        $message = 'Exception thrown by Guzzle client and has response';
        /** @var \GuzzleHttp\Psr7\Request $request */
        $request = $this->getMockBuilder(GuzzleRequest::class)
            ->disableOriginalConstructor()
            ->getMock();
        $exception = new ClientException($message, $request, $this->response);

        $this->guzzleClient->expects(static::once())
            ->method('post')
            ->willThrowException($exception);

        $this->response->expects(static::never())
            ->method('getBody');

        $this->client->execute($this->request, ConverterInterface::FORMAT_XML, Request::API_URL_XML);
    }

    /**
     * Create mock xml for request
     * @return string
     */
    private function getRequest()
    {
        $document = simplexml_load_file(__DIR__ . '/../data/request.xml');
        $result = $document->asXML();
        return $result;
    }

    /**
     * Create mock xml for response
     * @return string
     */
    private function getResponse()
    {
        $document = simplexml_load_file(__DIR__ . '/../data/response.xml');
        $result = $document->asXML();
        return $result;
    }
}
