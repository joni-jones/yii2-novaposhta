<?php
namespace components;

use Yii;
use jones\novaposhta\components\http\Client;
use jones\novaposhta\components\http\ClientFactory;
use jones\novaposhta\components\HttpClientInterface;
use jones\novaposhta\tests\TestCase;

/**
 * Class ClientFactoryTest
 * @package jones\novaposhta\tests\components\http
 */
class ClientFactoryTest extends TestCase
{
    /**
     * @var \jones\novaposhta\components\http\ClientFactory
     */
    private $httpClientFactory;

    protected function setUp()
    {
        $this->createApp();
        $this->httpClientFactory = Yii::createObject(ClientFactory::class);
    }

    /**
     * @covers \jones\novaposhta\components\http\ClientFactory::create()
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "novaposhta" component should be specified
     */
    public function testCreateWithConfigException()
    {
        $this->httpClientFactory->create();
    }

    /**
     * @covers \jones\novaposhta\components\http\ClientFactory::create()
     * @throws \yii\base\InvalidConfigException
     */
    public function testCreate()
    {
        Yii::$app->setComponents([
            'novaposhta' => [
                'class' => Client::class,
            ]
        ]);
        $client = $this->httpClientFactory->create();
        static::assertTrue($client instanceof HttpClientInterface);
    }
}
