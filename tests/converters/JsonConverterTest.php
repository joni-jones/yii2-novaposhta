<?php
namespace jones\novaposhta\tests\converters;

use jones\novaposhta\converters\JsonConverter;

/**
 * Class JsonConverterTest
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
     * @param array $expected
     * @param string $data
     * @covers \jones\novaposhta\converters\JsonConverter::decode
     * @dataProvider responseDataProvider
     */
    public function testDecode(array $expected, $data)
    {
        static::assertEquals($expected, $this->converter->decode($data));
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

    /**
     * Get items for response decoding testings
     * @return array
     */
    public function responseDataProvider()
    {
        return [
            [
                [
                    'success' => true,
                    'data' => [
                        [
                            'Description' => 'Kiev',
                            'Conglomerates' => [
                                '2f592fe1dcac', '2dsaxcw2e3dc'
                            ]
                        ],
                        [
                            'Description' => 'Odessa',
                            'Conglomerates' => [
                                'nv423dfv3sq1', 'mb3jrm55scv2'
                            ]
                        ]
                    ],
                    'errors' => [],
                    'warnings' => [],
                    'info' => []
                ],
                file_get_contents(__DIR__ . '/../data/response.json')
            ]
        ];
    }
}
