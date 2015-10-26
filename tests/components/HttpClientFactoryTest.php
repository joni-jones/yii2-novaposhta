<?php
namespace components;

use Yii;
use jones\novaposhta\components\HttpClient;
use jones\novaposhta\components\HttpClientFactory;
use jones\novaposhta\components\HttpClientInterface;
use jones\novaposhta\tests\TestCase;

/**
 * Class HttpClientFactoryTest
 * @package jones\novaposhta\tests\components
 */
class HttpClientFactoryTest extends TestCase
{
    /**
     * @var \jones\novaposhta\components\HttpClientFactory
     */
    private $httpClientFactory;

    protected function setUp()
    {
        $this->createApp();
        $this->httpClientFactory = Yii::createObject(HttpClientFactory::class);
    }

    /**
     * @covers \jones\novaposhta\components\HttpClientFactory::create()
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "novaposhta" component should be specified
     */
    public function testCreateWithConfigException()
    {
        $this->httpClientFactory->create();
    }

    /**
     * @covers \jones\novaposhta\components\HttpClientFactory::create()
     * @throws \yii\base\InvalidConfigException
     */
    public function testCreate()
    {
        Yii::$app->setComponents([
            'novaposhta' => [
                'class' => HttpClient::class,
                'api_key' => '34fs43Ndw'
            ]
        ]);
        $client = $this->httpClientFactory->create();
        static::assertTrue($client instanceof HttpClientInterface);
    }
}
