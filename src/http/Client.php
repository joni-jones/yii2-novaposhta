<?php
namespace jones\novaposhta\http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException as GuzzleClientException;
use jones\novaposhta\request\RequestInterface;
use Yii;

/**
 * Class Client
 */
class Client implements ClientInterface
{
    /**
     * Concrete client to process http requests
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * SSL verify
     * @var boolean
     */
    private $verify;

    /**
     * Path to SSL pem file
     * @var string
     */
    private $certPath;

    /**
     * @param GuzzleClient $client
     * @param bool $verify
     * @param string $certPath
     */
    public function __construct(GuzzleClient $client, $verify = false, $certPath = '')
    {
        $this->client = $client;
        $this->verify = $verify;
        $this->certPath = $certPath;
    }

    /**
     * Execute http request
     * @param RequestInterface $request
     * @param string $contentType
     * @param string $url
     * @return string
     * @throws \jones\novaposhta\http\ClientException
     */
    public function execute(RequestInterface $request, $contentType, $url)
    {
        $options = [
            'verify' => $this->verify ? $this->certPath : false,
            'headers' => [
                'content-type' => $contentType
            ],
            'body' => $request->getBody()
        ];
        try {
            $response = $this->client->post($url, $options);
        } catch (GuzzleClientException $e) {
            Yii::error($e->getRequest());
            if ($e->hasResponse()) {
                Yii::error($e->getResponse());
            }
            throw new ClientException($e);
        }
        Yii::trace($response);
        return (string) $response->getBody();
    }
}
