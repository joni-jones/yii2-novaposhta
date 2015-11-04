<?php
namespace jones\novaposhta\tests\components\http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use jones\novaposhta\components\http\Client;
use jones\novaposhta\components\Request;
use Yii;

/**
 * Class ClientTest
 * @package jones\novaposhta\tests\components\http
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \jones\novaposhta\components\http\Client
     */
    private $httpClient;

    /**
     * @var \jones\novaposhta\components\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \GuzzleHttp\Client
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
        $mockHandler = new MockHandler([$this->response]);
        $handler = HandlerStack::create($mockHandler);
        $this->guzzleClient = new GuzzleClient(['handler' => $handler]);

        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMock();

        $this->httpClient = Yii::createObject(Client::class, [
            $this->guzzleClient,
            'https://test.api.com'
        ]);
    }

    /**
     * @covers \jones\novaposhta\components\http\Client::execute()
     */
    public function testExecute()
    {
        $this->request->expects(static::once())
            ->method('getBody')
            ->willReturn($this->getSuccessRequest());

        $this->response->expects(static::never())
            ->method('getRequest');
        $this->response->expects(static::never())
            ->method('hasResponse');
        $this->response->expects(static::never())
            ->method('getRequest');
        $this->response->expects(static::once())
            ->method('getBody')
            ->willReturn($this->getSuccessResponse());

        $response = $this->httpClient->execute($this->request);
        static::assertTrue(is_array($response));
        static::assertTrue(!empty($response['successCode']));
        static::assertEquals(Request::SUCCESS_STATUS, $response['successCode']);
    }

    /**
     * Create mock xml for request
     * @return string
     */
    private function getSuccessRequest()
    {
        $document = simplexml_load_file(__DIR__ . '/data/success_response.xml');
        $result = $document->asXML();
        return $result;
    }

    /**
     * Create mock xml for response
     * @return string
     */
    private function getSuccessResponse()
    {
        $document = simplexml_load_file(__DIR__ . '/data/success_response.xml');
        $result = $document->asXML();
        return $result;
    }
}
