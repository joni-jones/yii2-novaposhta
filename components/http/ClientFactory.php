<?php
namespace jones\novaposhta\components\http;

use GuzzleHttp\Client as GuzzleClient;
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
        if (empty($components['novaposhta'])) {
            throw new InvalidConfigException('The "novaposhta" component should be specified');
        }
        if (empty($components['novaposhta']['url'])) {
            throw new InvalidConfigException('The api "url" should be specified');
        }
        $httpClient = Yii::createObject($components['novaposhta']['class'], [
            $client,
            $components['novaposhta']['url']
        ]);
        return $httpClient;
    }
}