<?php
namespace jones\novaposhta\components;

use Yii;
use GuzzleHttp\Client;
use yii\base\InvalidConfigException;

/**
 * Class HttpClientFactory
 * @package jones\novaposhta\components
 */
class HttpClientFactory
{
    /**
     * Create http client object
     * @return \jones\novaposhta\components\HttpClientInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function create()
    {
        $client = new Client();
        $components = Yii::$app->getComponents();
        if (empty($components['novaposhta'])) {
            throw new InvalidConfigException('The "novaposhta" component should be specified');
        }
        $httpClient = Yii::createObject($components['novaposhta']['class'], [$client, $components['novaposhta']]);
        return $httpClient;
    }
}