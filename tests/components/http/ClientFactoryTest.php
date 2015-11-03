<?php
namespace components;

use jones\novaposhta\components\http\Client;
use jones\novaposhta\components\http\ClientFactory;
use jones\novaposhta\components\HttpClientInterface;
use jones\novaposhta\tests\TestCase;
use Yii;

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
    public function testCreateWithComponentClassException()
    {
        $this->httpClientFactory->create();
    }

    /**
     * @covers \jones\novaposhta\components\http\ClientFactory::create()
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The api "url" should be specified
     */
    public function testCreateWithComponentUrlException()
    {
        Yii::$app->setComponents([
            'novaposhta' => [
                'class' => Client::class,
            ]
        ]);
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
                'url' => 'https://test.api.com'
            ]
        ]);
        $client = $this->httpClientFactory->create();
        static::assertTrue($client instanceof HttpClientInterface);
    }
}
