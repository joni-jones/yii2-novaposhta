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
    /**
     * Create request object
     * @return \jones\novaposhta\request\RequestInterface
     * @throws InvalidConfigException
     */
    public function create()
    {
        $name = 'novaposhta';
        $components = Yii::$app->getComponents();
        if (empty($components[$name]) || empty($components[$name]['api_key'])) {
            throw new InvalidConfigException('The "api_key" of component should be specified');
        }
        if (empty($components[$name]['format'])) {
            throw new InvalidConfigException('The "format" should be specified');
        }
        $converterFactory = new ConverterFactory();
        $converter = $converterFactory->create($components[$name]['format']);
        $clientFactory = new ClientFactory();
        $request = Yii::createObject(Request::class, [
            $converter,
            $clientFactory,
            $components['novaposhta']['api_key']
        ]);
        return $request;
    }
}