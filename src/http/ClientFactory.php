<?php
namespace jones\novaposhta\http;

use GuzzleHttp\Client as GuzzleClient;
use Yii;

/**
 * Class ClientFactory
 * @package jones\novaposhta\http
 */
class ClientFactory
{
    /**
     * Create concrete implementation of http client interface
     * @return \jones\novaposhta\http\ClientInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function create()
    {
        $client = new GuzzleClient();
        $httpClient = Yii::createObject(Client::class, [$client]);
        return $httpClient;
    }
}