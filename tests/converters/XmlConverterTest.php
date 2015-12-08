<?php
namespace jones\novaposhta\tests\converters;

use jones\novaposhta\converters\XmlConverter;
use SimpleXMLElement;

/**
 * Class XmlConverterTest
 * @package jones\novaposhta\tests\converters
 */
class XmlConverterTest extends \PHPUnit_Framework_TestCase
{
    /** @var \jones\novaposhta\converters\XmlConverter */
    private $converter;

    protected function setUp()
    {
        $this->converter = new XmlConverter();
    }

    /**
     * @param array $data
     * @param string $expected
     * @covers \jones\novaposhta\converters\XmlConverter::encode
     * @dataProvider requestDataProvider
     */
    public function testEncode(array $data, $expected)
    {
        $actual = $this->converter->encode($data);
        static::assertEquals($this->xmlToDomElement($expected), $this->xmlToDomElement($actual));
    }

    /**
     * @param array $expected
     * @param string $data
     * @covers \jones\novaposhta\converters\XmlConverter::decode
     * @dataProvider responseDataProvider
     */
    public function testDecode(array $expected, $data)
    {
        $actual = $this->converter->decode($data);
        static::assertEquals($expected, $actual);
    }

    /**
     * @param array $data
     * @covers \jones\novaposhta\converters\XmlConverter::createDocument
     * @dataProvider requestDataProvider
     */
    public function testCreateDocument(array $data)
    {
        $encoded = $this->converter->encode($data);
        $document = simplexml_load_string($encoded);
        static::assertNotEmpty($document->apiKey);
    }

    /**
     * @param array $data
     * @covers \jones\novaposhta\converters\XmlConverter::appendChildren
     * @dataProvider requestDataProvider
     */
    public function testAppendChildren(array $data)
    {
        $encoded = $this->converter->encode($data);
        $document = simplexml_load_string($encoded);
        static::assertNotEmpty((array) $document->methodProperties);
        static::assertNotEmpty((array) $document->methodProperties->FindByString);
        static::assertEquals('Kiev', (string) $document->methodProperties->FindByString);
    }

    /**
     * Get testing data
     * @return array
     */
    public function requestDataProvider()
    {
        return [
            [
                [
                    'apiKey' => 'ieu2iqw4o',
                    'modelName' => 'Address',
                    'calledMethod' => 'getCities',
                    'methodProperties' => [
                        'FindByString' => 'Kiev'
                    ]
                ],
                file_get_contents(__DIR__ . '/../data/request.xml')
            ],
        ];
    }

    /**
     * Get data for response decoding testing
     * @return array
     */
    public function responseDataProvider()
    {
        return [
            [
                [
                    'success' => 'true',
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
                file_get_contents(__DIR__ . '/../data/response.xml')
            ]
        ];
    }

    /**
     * @covers \jones\novaposhta\converters\XmlConverter::getContentType
     */
    public function testGetContentType()
    {
        static::assertEquals('application/xml', $this->converter->getContentType());
    }

    /**
     * @covers \jones\novaposhta\converters\XmlConverter::getType
     */
    public function testGetType()
    {
        static::assertEquals('xml', $this->converter->getType());
    }

    /**
     * Convert xml string to DomElement
     * @param string $xml
     * @return \DOMElement
     */
    private function xmlToDomElement($xml)
    {
        $document = new SimpleXMLElement($xml);
        $domElement = dom_import_simplexml($document);
        return $domElement;
    }
}
