<?php
namespace jones\novaposhta\components;

/**
 * Interface HttpClientInterface
 * @package jones\novaposhta\components
 */
interface HttpClientInterface
{
    public function execute(RequestInterface $request);
}