<?php
namespace jones\novaposhta\components;

use jones\novaposhta\Api;
use jones\novaposhta\components\http\ClientFactory;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class RequestFactory
 * @package jones\novaposhta\components
 */
class RequestFactory
{
    /**
     * Create request object
     * @return \jones\novaposhta\components\RequestInterface
     * @throws InvalidConfigException
     */
    public function create()
    {
        $components = Yii::$app->getComponents();
        if (empty($components[Api::COMPONENT_NAME]) || empty($components[Api::COMPONENT_NAME]['api_key'])) {
            throw new InvalidConfigException('The "api_key" of component should be specified');
        }
        $clientFactory = new ClientFactory();
        $request = Yii::createObject(Request::class, [$clientFactory, $components[Api::COMPONENT_NAME]]);
        return $request;
    }
}

 