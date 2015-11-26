<?php
namespace jones\novaposhta\converters;

/**
 * Interface ConverterInterface
 * @package jones\novaposhta\converters
 */
interface ConverterInterface
{

    /**
     * Convert array to specified format
     * @param array $params
     * @return mixed
     */
    public function encode(array $params);

    /**
     * Decode data to array
     * @param $data
     * @return array
     */
    public function decode($data);

    /**
     * Get content type of current converted data
     * @return string
     */
    public function getContentType();
}
