<?php
namespace jones\novaposhta\tests\request;

use jones\novaposhta\Api;
use jones\novaposhta\request\RequestFactory;
use jones\novaposhta\request\RequestInterface;
use jones\novaposhta\tests\TestCase;
use Yii;

/**
 * Class RequestFactoryTest
 * @package jones\novaposhta\tests
 */
class RequestFactoryTest extends TestCase
{
    /**
     * @var \jones\novaposhta\request\RequestFactory
     */
    private $requestFactory;

    protected function setUp()
    {
        $this->createApp();
        $this->requestFactory = Yii::createObject(RequestFactory::class);
    }

    /**
     * @covers \jones\novaposhta\request\RequestFactory::create
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "api_key" of component should be specified
     */
    public function testCreateWithApiKeyException()
    {
        $this->requestFactory->create();
    }

    /**
     * @covers \jones\novaposhta\request\RequestFactory::create
     * @expectedException \yii\base\InvalidConfigException
     * @expectedExceptionMessage The "format" should be specified
     */
    public function testCreateWithFormatException()
    {
        Yii::$app->setComponents([
            'novaposhta' => [
                'class' => Api::class,
                'api_key' => 'ruw4E21wV',
            ]
        ]);
        $this->requestFactory->create();
    }

    /**
     * @covers \jones\novaposhta\request\RequestFactory::create
     */
    public function testCreate()
    {
        Yii::$app->setComponents([
            'novaposhta' => [
                'class' => Api::class,
                'api_key' => 'ruw4E21wV',
                'format' => 'json'
            ]
        ]);
        $request = $this->requestFactory->create();
        static::assertTrue($request instanceof RequestInterface);
    }
}
