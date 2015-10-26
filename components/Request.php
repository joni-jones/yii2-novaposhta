<?php

namespace jones\novaposhta\components;

/**
 * Class Request
 * @package jones\novaposhta\components
 */
class Request implements RequestInterface
{
    const METHOD_POST = 'POST';

    const SUCCESS_STATUS = 200;

    public function getBody() {}

    public function getKey() {}

    public function getFilters() {}

    public function setFilters(array $filters) {}
}