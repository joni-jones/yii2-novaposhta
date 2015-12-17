<?php
namespace jones\novaposhta\converters;

use SimpleXMLElement;

/**
 * Class XmlConverter
 * @package jones\novaposhta\converters
 */
class XmlConverter implements ConverterInterface
{
    const CONTENT_TYPE = 'application/xml';

    /**
     * @inheritdoc
     */
    public function encode(array $params)
    {
        $document = $this->createDocument();
        $this->appendChildren($document, $params);
        return $document->asXML();
    }

    /**
     * @inheritdoc
     */
    public function decode($data)
    {
        $document = simplexml_load_string($data);
        $data = json_encode((array) $document);
        $items = json_decode($data, true);
        return $this->removeItemKey($items);
    }

    /**
     * @inheritdoc
     */
    public function getContentType()
    {
        return self::CONTENT_TYPE;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return ConverterInterface::FORMAT_XML;
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
    private function appendChildren(SimpleXMLElement $node, array $data)
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $child = $node->addChild($key);
                $this->appendChildren($child, $item);
            } else {
                $node->addChild($key, $item);
            }
        }
    }

    /**
     * Recursively remove `item` key from array,
     * this method needed to create compatible data with other converters
     * @param array $data
     * @return array
     */
    private function removeItemKey(array $data)
    {
        foreach ($data as $key => &$value) {
            if (is_array($value)) {
                $value = $this->removeItemKey($value);
            }
            // set current value instead array[key]
            // this allow to remove `item` key without data losing
            if ($key === 'item') {
                $data = (array) $value;
            }
        }
        unset($value);
        return $data;
    }
}
