<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Api;
use jones\novaposhta\request\RequestFactory;

/**
 * Class ApiTest
 */
class ApiTest extends TestCase
{
    /**
     * @var \jones\novaposhta\Api
     */
    private $api;

    protected function setUp()
    {
        $requestFactory = $this->getRequestFactory();
        $this->api = new Api($requestFactory);
    }

    /**
     * @covers \jones\novaposhta\Api::__construct
     */
    public function testApiConstructor()
    {
        $this->invokeConstructor(Api::class, [
            new RequestFactory()
        ]);
    }
}
