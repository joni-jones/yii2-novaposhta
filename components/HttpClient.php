<?php
namespace jones\novaposhta\components;

use Yii;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

/**
 * Class HttpClient
 * @package jones\novaposhta\components
 */
class HttpClient implements HttpClientInterface
{
    const CONTENT_TYPE = 'text/xml';

    const BASE_URL = 'http://orders.novaposhta.ua/xml.php';

    private $apiKey;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(Client $httpClient, array $config = [])
    {
        $this->client = $httpClient;
        if (empty($config['api_key'])) {
            throw new InvalidConfigException('The "api_key" should be specified');
        }
        $this->apiKey = $config['api_key'];
    }

    /**
     * Execute http request
     * @param RequestInterface $request
     * @return array
     * @throws HttpClientException
     */
    public function execute(RequestInterface $request)
    {
        $options = [
            'headers' => [
                'content-type' => self::CONTENT_TYPE
            ],
            'body' => $this->getRequestBody($request)
        ];
        try {
            $response = $this->client->post(self::BASE_URL, $options);
        } catch (ClientException $e) {
            Yii::error($e->getRequest());
            if ($e->hasResponse()) {
                Yii::error($e->getResponse());
            }
            throw new HttpClientException($e->getMessage());
        }
        Yii::trace($response);
        return $this->prepareResponse($response);
    }

    /**
     * Get request params
     * @param \jones\novaposhta\components\RequestInterface $request
     * @return string
     */
    private function getRequestBody(RequestInterface $request)
    {
        $document = new \SimpleXMLElement('file');
        $document->addChild('auth', $this->apiKey);
        $data = $request->getBody();
        $key = $request->getKey();
        if ($key) {
            $document->addChild($request->getKey(), !empty($data) ? $data : '');
        }
        $filters = $request->getFilters();
        if (!empty($filters)) {
            $document->addChild('filter', $filters);
        }
        return $document->asXML();
    }

    /**
     * Convert string xml response to array
     * @param ResponseInterface $response
     * @return array
     */
    private function prepareResponse(ResponseInterface $response)
    {
        $document = new \SimpleXMLElement($response->getBody());
        return Json::decode(Json::encode($document), true);
    }
}

/**
 * Class HttpClientException
 * @package jones\novaposhta\components
 */
class HttpClientException extends Exception{}