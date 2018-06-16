<?php
namespace jones\novaposhta\request;

use jones\novaposhta\converters\ConverterFactory;
use Yii;
use yii\base\InvalidConfigException;
use jones\novaposhta\http\ClientFactory;

/**
 * Class RequestFactory
 * @package jones\novaposhta\request
 */
class RequestFactory
{

    private $apiKey = null;

    private $format = 'json';

    public function __construct($apiKey = null, $format = 'json')
    {
        $this->apiKey = $apiKey;
        $this->format = $format;
    }


    /**
     * Create request object
     * @return \jones\novaposhta\request\RequestInterface
     * @throws InvalidConfigException
     */
    public function create()
    {

        if(!$this->format || !$this->apiKey) {
            $this->initGlobalConfig();
        }

        $converterFactory = new ConverterFactory();
        $converter = $converterFactory->create($this->format);
        $clientFactory = new ClientFactory();

        $request = Yii::createObject(Request::class, [
            $converter,
            $clientFactory,
            $this->apiKey
        ]);

        return $request;
    }

    public function initGlobalConfig() {
        $name = 'novaposhta';
        $components = Yii::$app->getComponents();
        $config = !empty($components[$name]) ? $components[$name] : [];

        if (empty($config['api_key'])) {
            throw new InvalidConfigException('The "api_key" of component should be specified');
        }

        $this->apiKey = $config['api_key'];

        if (empty($config['format'])) {
            throw new InvalidConfigException('The "format" should be specified');
        }

        $this->format = $config['format'];

        return $config;

    }
}