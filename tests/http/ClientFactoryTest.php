<?php
namespace jones\novaposhta\tests\http;

use jones\novaposhta\http\ClientFactory;
use jones\novaposhta\http\ClientInterface;
use jones\novaposhta\tests\TestCase;
use Yii;

/**
 * Class ClientFactoryTest
 */
class ClientFactoryTest extends TestCase
{
    /**
     * @var \jones\novaposhta\http\ClientFactory
     */
    private $httpClientFactory;

    protected function setUp()
    {
        $this->httpClientFactory = new ClientFactory();
    }

    /**
     * @covers \jones\novaposhta\http\ClientFactory::create
     */
    public function testCreate()
    {
        $this->createApp();
        $client = $this->httpClientFactory->create();
        static::assertTrue($client instanceof ClientInterface);
    }

    /**
     * @covers \jones\novaposhta\http\ClientFactory::create
     */
    public function testCreateWithVerify()
    {
        $this->createApp([
            'components' => [
                'novaposhta' => [
                    'class' => 'jones\novaposhta\Api',
                    'verify' => true,
                    'certPath' => '/etc/ssl/tmp.pem'
                ]
            ]
        ]);
        $client = $this->httpClientFactory->create();
        static::assertTrue($client instanceof ClientInterface);
    }
}
