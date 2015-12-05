<?php
namespace jones\novaposhta\converters;

/**
 * Interface ConverterInterface
 * @package jones\novaposhta\converters
 */
interface ConverterInterface
{
    const FORMAT_JSON = 'json';

    const FORMAT_XML = 'xml';

    /**
     * Convert array to specified format
     * @param array $params
     * @return string|false
     */
    public function encode(array $params);

    /**
     * Decode data to array
     * @param string $data
     * @return array
     */
    public function decode($data);

    /**
     * Get content type of current converted data
     * @return string
     */
    public function getContentType();

    /**
     * Get type of concrete format implementation
     * @return string
     */
    public function getType();
}
