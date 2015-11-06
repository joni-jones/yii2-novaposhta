<?php

namespace jones\novaposhta\components;

use jones\novaposhta\components\http\ClientFactory;
use SimpleXMLElement;
use yii\base\InvalidConfigException;

/**
 * Class Request
 * @package jones\novaposhta\components
 */
class Request implements RequestInterface
{

    const SUCCESS_STATUS = 200;

    /**
     * @var string api key
     */
    private $apiKey;

    /**
     * @var \SimpleXMLElement
     */
    private $document;

    /**
     * @var \jones\novaposhta\components\http\ClientFactory
     */
    private $httpClientFactory;

    public function __construct(ClientFactory $factory, array $config = [])
    {
        if (empty($config['api_key'])) {
            throw new InvalidConfigException('The "api_key" should be specified');
        }
        $this->apiKey = $config['api_key'];
        $this->httpClientFactory = $factory;
    }

    /**
     * Return body of request in xml format or empty string
     * @return string
     */
    public function getBody()
    {
        return !empty($this->document) ? $this->document->asXML() : '';
    }

    /**
     * Create request from params
     * @param SimpleXMLElement $params
     * @param string $filter
     * @return $this
     */
    public function build(SimpleXMLElement $params, $filter = '')
    {
        $this->createRequestDocument();
        $this->document = $this->merge($this->document, $params);
        if (!empty($filter)) {
            $this->appendFilter($filter);
        }
        return $this;
    }

    /**
     * Create request xml document
     */
    private function createRequestDocument()
    {
        $this->document = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><file/>');
        $this->document->addChild('auth', $this->apiKey);
    }

    /**
     * Process request
     * @return array
     * @throws InvalidConfigException
     */
    public function execute()
    {
        $client = $this->httpClientFactory->create();
        return $client->execute($this);
    }

    /**
     * Append filter node to request
     * @param $filter
     */
    private function appendFilter($filter)
    {
        $this->document->addChild('filter', $filter);
    }

    /**
     * Merge two xml documents by
     * @param SimpleXMLElement $root
     * @param SimpleXMLElement $merge
     * @param string $tag
     * @return SimpleXMLElement
     */
    private function merge(SimpleXMLElement $root, SimpleXMLElement $merge, $tag = 'file')
    {
        // convert xml to dom document
        $to = new \DOMDocument();
        $to->loadXML($root->asXML());
        $from = new \DOMDocument();
        $from->loadXML($merge->asXML());

        // get file node from root document
        $file = $to->getElementsByTagName($tag)->item(0);
        // get file node from merge document
        $item = $from->getElementsByTagName($tag)->item(0);

        // iterate and append all nodes from merge document to root
        foreach ($item->childNodes as $child) {
            $node = $to->importNode($child, true);
            $file->appendChild($node);
        }

        $document = new SimpleXMLElement($to->saveXML());
        return $document;
    }
}