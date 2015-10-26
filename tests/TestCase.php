<?php
namespace jones\novaposhta\tests;

use Yii;
use yii\console\Application;
use yii\helpers\ArrayHelper;

/**
 * Class TestCase
 * @package jones\novaposhta\tests
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
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
                'components' => []
            ],
            $config
        );
        new Application($params);
    }

    protected function tearDown()
    {
        Yii::$app = null;
    }
}