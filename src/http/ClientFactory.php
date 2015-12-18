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
        $name = 'novaposhta';
        $components = Yii::$app->getComponents();
        $verify = false;
        $certPath = '';
        if (!empty($components[$name]) && !empty($components[$name]['verify'])) {
            $certPath = $components[$name]['certPath'];
        }
        $httpClient = Yii::createObject(Client::class, [$client, $verify, $certPath]);
        return $httpClient;
    }
}
