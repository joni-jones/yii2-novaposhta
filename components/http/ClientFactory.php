<?php
namespace jones\novaposhta\components\http;

use Yii;
use yii\base\InvalidConfigException;
use GuzzleHttp\Client as GuzzleClient;

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
        if (empty($components['novaposhta'])) {
            throw new InvalidConfigException('The "novaposhta" component should be specified');
        }
        $httpClient = Yii::createObject($components['novaposhta']['class'], [$client]);
        return $httpClient;
    }
}