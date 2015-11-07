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

    public function __construct(RequestFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * Validate api request params
     * @throws NotSupportedException
     */
    public function validate()
    {
        throw new NotSupportedException('This method should be implemented in children classes');
    }

    /**
     * Create xml request document from array params
     * @param array $params
     * @param string $rootNode
     * @return SimpleXMLElement
     */
    public function createRequestFromArray(array $params, $rootNode)
    {
        $document = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><file/>');
        $node = $document->addChild($rootNode);
        $this->appendAttributes($node, $params);
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
