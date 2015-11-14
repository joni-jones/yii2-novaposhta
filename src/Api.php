<?php
namespace jones\novaposhta;

use jones\novaposhta\components\RequestFactory;
use SimpleXMLElement;
use yii\base\NotSupportedException;

/**
 * Class Api
 * @package jones\novaposhta\components
 */
class Api
{
    const COMPONENT_NAME = 'novaposhta';

    /**
     * @var \jones\novaposhta\components\RequestFactory
     */
    protected $requestFactory;

    private $errors = [];

    public function __construct(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * Validate api request params
     * @param array $params
     * @throws NotSupportedException
     */
    public function validate(array $params)
    {
        throw new NotSupportedException('This method should be implemented in children classes');
    }

    /**
     * Store error
     * @param $error
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Store list of errors
     * @param array $items
     */
    public function addErrors(array $items)
    {
        foreach ($items as $attribute => $errors) {
            if (is_array($errors)) {
                foreach ($errors as $error) {
                    $this->addError($error);
                }
            } else {
                $this->addError($errors);
            }
        }
    }

    /**
     * Get all errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Create xml request document from array params
     * @param array $params
     * @param string $rootNode
     * @return SimpleXMLElement
     */
    public function createRequestFromArray(array $params, $rootNode)
    {
        $document = $this->createDocument();
        $node = $document->addChild($rootNode);
        $this->appendAttributes($node, $params);
        return $document;
    }

    /**
     * Create xml request document for simple value
     * @param $value
     * @param $rootNode
     * @return SimpleXMLElement
     */
    public function createRequest($value, $rootNode)
    {
        $document = $this->createDocument();
        $document->addChild($rootNode, $value);
        return $document;
    }

    /**
     * Execute request
     * @param SimpleXMLElement $document
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \jones\novaposhta\components\http\HttpClientException
     */
    protected function execute(SimpleXMLElement $document)
    {
        $request = $this->requestFactory->create();
        return $request->build($document)->execute();
    }

    /**
     * Create xml document for request
     * @return SimpleXMLElement
     */
    private function createDocument()
    {
        $document = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><file/>');
        return $document;
    }

    /**
     * Recursively append array values to received xml node
     * @param SimpleXMLElement $node
     * @param array $data
     */
    private function appendAttributes(SimpleXMLElement $node, array $data)
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $child = $node->addChild($key);
                $this->appendAttributes($child, $item);
            } else {
                $node->addAttribute($key, $item);
            }
        }
    }
}
