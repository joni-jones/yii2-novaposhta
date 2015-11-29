<?php
namespace jones\novaposhta\tests\converters;

use jones\novaposhta\converters\JsonConverter;

/**
 * Class JsonConverterTest
 * @package jones\novaposhta\tests\converters
 */
class JsonConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \jones\novaposhta\converters\JsonConverter */
    private $converter;

    protected function setUp()
    {
        $this->converter = new JsonConverter();
    }

    /**
     * @covers \jones\novaposhta\converters\JsonConverter::encode
     */
    public function testEncode()
    {
        $data = [
            'apiKey' => 'ui6dsf21',
            'modelName' => 'Address',
            'calledMethod' => 'getCities',
            'methodProperties' => [
                'FindByString' => 'Kiev'
            ]
        ];
        static::assertEquals(json_encode($data), $this->converter->encode($data));
    }

    /**
     * @covers \jones\novaposhta\converters\JsonConverter::decode
     */
    public function testDecode()
    {
        $data = '{"apiKey":"ui6dsf21","modelName":"Address","calledMethod":"getCities","methodProperties":
            {"FindByString":"Kiev"}}';
        static::assertEquals(json_decode($data, true), $this->converter->decode($data));
    }

    /**
     * @covers \jones\novaposhta\converters\JsonConverter::getContentType
     */
    public function testGetContentType()
    {
        static::assertEquals('application/json', $this->converter->getContentType());
    }

    /**
     * @covers \jones\novaposhta\converters\JsonConverter::getType
     */
    public function testGetType()
    {
        static::assertEquals('json', $this->converter->getType());
    }
}
