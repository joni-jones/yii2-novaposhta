<?php
namespace jones\novaposhta\request;

/**
 * Interface RequestInterface
 */
interface RequestInterface
{
    /**
     * Prepare request params
     * @param string $modelName
     * @param string $calledMethod
     * @param array $params
     * @return RequestInterface
     */
    public function build($modelName, $calledMethod, array $params);

    /**
     * Execute http request
     * @return array
     */
    public function execute();

    /**
     * Get body of request
     * @return string
     */
    public function getBody();
}
