<?php
namespace jones\novaposhta\http;

use jones\novaposhta\request\RequestInterface;

/**
 * Interface ClientInterface
 * @package jones\novaposhta\http
 */
interface ClientInterface
{
    /**
     * Execute http request
     * @param RequestInterface $request
     * @param string $contentType
     * @param string $url
     * @return string
     */
    public function execute(RequestInterface $request, $contentType, $url);
}