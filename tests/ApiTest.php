<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Api;
use jones\novaposhta\request\RequestFactory;
use yii\base\Model;

/**
 * Class ApiTest
 * @package jones\novaposhta\tests
 */
class ApiTest extends TestCase
{
    /**
     * @var \jones\novaposhta\Api
     */
    private $api;

    /**
     * @var \yii\base\Model
     */
    private $model;

    protected function setUp()
    {
        $requestFactory = $this->getRequestFactory();
        $this->model = $this->getMockBuilder(Model::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->api = new Api($this->model, $requestFactory);
    }

    /**
     * @covers \jones\novaposhta\Api::__construct
     */
    public function testApiConstructor()
    {
        $this->invokeConstructor(Api::class, [
            new Model(),
            new RequestFactory()
        ]);
    }
}
