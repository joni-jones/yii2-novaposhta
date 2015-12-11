<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\request\Request;
use jones\novaposhta\request\RequestFactory;
use Yii;
use yii\console\Application;
use yii\helpers\ArrayHelper;

/**
 * Class TestCase
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    const API_KEY = '3wb23DmB1';

    /**
     * @var \jones\novaposhta\request\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * @var \jones\novaposhta\Api
     */
    protected $model;

    /**
     * Create app for run tests
     * @param array $config
     * @throws \yii\base\InvalidConfigException
     */
    protected function createApp(array $config = [])
    {
        $params = ArrayHelper::merge(
            [
                'id' => 'testapp',
                'basePath' => __DIR__,
                'vendorPath' => __DIR__ . '/../vendor',
                'components' => [
                    'i18n' => [
                        'translations' => [
                            '*' => [
                                'class' => 'yii\i18n\PhpMessageSource',
                                'basePath' => '@novaposhta/messages',
                                'sourceLanguage' => 'en',
                            ],
                        ]
                    ],
                ]
            ],
            $config
        );
        new Application($params);
    }

    protected function tearDown()
    {
        Yii::$app = null;
    }

    /**
     * Create mock for request and get request factory mock
     * @return \jones\novaposhta\request\RequestFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestFactory()
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['build', 'execute', '__wakeup', 'getBody'])
            ->getMock();

        $this->request->expects(static::any())
            ->method('build')
            ->willReturnSelf();

        $requestFactory = $this->getMockBuilder(RequestFactory::class)
            ->setMethods(['create'])
            ->getMock();
        $requestFactory->expects(static::any())
            ->method('create')
            ->willReturn($this->request);
        return $requestFactory;
    }

    /**
     * Get mock for constructor of class
     * @param string $className
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getConstructorMock($className)
    {
        $mock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    /**
     * Create object from received class name and invoke constructor with arguments
     * @param string $className
     * @param array $arguments
     */
    protected function invokeConstructor($className, array $arguments = [])
    {
        $mock = $this->getConstructorMock($className);
        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        $constructor->invokeArgs($mock, $arguments);
    }

    /**
     * Skip model attributes
     */
    protected function flushAttributes()
    {
        $values = array_fill(0, count($this->model->attributes()), null);
        $attributes = array_combine($this->model->attributes(), $values);
        $this->model->setAttributes($attributes);
    }
}
