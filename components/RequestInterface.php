<?php
namespace jones\novaposhta\components;

/**
 * Interface RequestInterface
 * @package jones\novaposhta\components
 */
interface RequestInterface
{

    /**
     * Return body of request
     * @return string in xml format
     */
    public function getBody();

    /**
     * Create request from params
     * @param \SimpleXMLElement $params
     * @param string $filter
     */
    public function build(\SimpleXMLElement $params, $filter = '');

    /**
     * Process request
     * @return array
     */
    public function execute();
}