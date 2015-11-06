<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Api;
use jones\novaposhta\components\RequestFactory;
use jones\novaposhta\components\RequestInterface;
use Yii;

/**
 * Class RequestFactoryTest
 * @package jones\novaposhta\tests
 */
class RequestFactoryTest extends TestCase
{
    /**
     * @var \jones\novaposhta\components\RequestFactory
     */
    private $requestFactory;

    protected function setUp()
    {
        $this->createApp();
        $this->requestFactory = Yii::createObject(RequestFactory::class);
    }

    /**
     * @covers \jones\novaposhta\components\RequestFactory::create()
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "api_key" of component should be specified
     */
    public function testCreateWithApiKeyException()
    {
        $this->requestFactory->create();
    }

    /**
     * @covers \jones\novaposhta\components\RequestFactory::create()
     */
    public function testCreate()
    {
        Yii::$app->setComponents([
            Api::COMPONENT_NAME => [
                'class' => Api::class,
                'api_key' => 'ruw4E21wV'
            ]
        ]);
        $request = $this->requestFactory->create();
        static::assertTrue($request instanceof RequestInterface);
    }
}

 