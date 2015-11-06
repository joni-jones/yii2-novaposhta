<?php
namespace jones\novaposhta\components\http;

use GuzzleHttp\Client as GuzzleClient;
use jones\novaposhta\Api;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class ClientFactory
 * @package jones\novaposhta\components\http
 */
class ClientFactory
{

    /**
     * Create http client object
     * @return \jones\novaposhta\components\HttpClientInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function create()
    {
        $client = new GuzzleClient();
        $components = Yii::$app->getComponents();
        if (empty($components[Api::COMPONENT_NAME])) {
            throw new InvalidConfigException('The "novaposhta" component should be specified');
        }
        if (empty($components[Api::COMPONENT_NAME]['url'])) {
            throw new InvalidConfigException('The api "url" should be specified');
        }
        $httpClient = Yii::createObject(Client::class, [
            $client,
            $components[Api::COMPONENT_NAME]['url']
        ]);
        return $httpClient;
    }
}