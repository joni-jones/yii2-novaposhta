<?php
namespace jones\novaposhta\tests\converters;

use jones\novaposhta\converters\ConverterFactory;
use jones\novaposhta\converters\ConverterInterface;
use jones\novaposhta\converters\JsonConverter;
use jones\novaposhta\converters\XmlConverter;

/**
 * Class ConverterFactoryTest
 * @package jones\novaposhta\tests
 */
class ConverterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var \jones\novaposhta\converters\ConverterFactory */
    private $converterFactory;

    protected function setUp()
    {
        $this->converterFactory = new ConverterFactory();
    }

    /**
     * @covers \jones\novaposhta\converters\ConverterFactory::create
     * @expectedException \yii\base\NotSupportedException
     * @expectedExceptionMessage The specified convert format not supported
     */
    public function testCreateWithUnsupportedFormat()
    {
        $this->converterFactory->create('html');
    }

    /**
     * @covers \jones\novaposhta\converters\ConverterFactory::create
     */
    public function testCreateJsonConverter()
    {
        $converter = $this->converterFactory->create(ConverterInterface::FORMAT_JSON);
        static::assertInstanceOf(JsonConverter::class, $converter);
    }

    /**
     * @covers \jones\novaposhta\converters\ConverterFactory::create
     */
    public function testCreateXmlConverter()
    {
        $converter = $this->converterFactory->create(ConverterInterface::FORMAT_XML);
        static::assertInstanceOf(XmlConverter::class, $converter);
    }
}
