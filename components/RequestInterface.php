<?php
namespace jones\novaposhta\components;

interface RequestInterface
{
    public function getKey();

    public function getBody();

    public function getFilters();

    public function setFilters(array $filters);
}