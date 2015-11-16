<?php
namespace jones\novaposhta\tests\helpers;

use jones\novaposhta\helpers\Formatter;
use Yii;

/**
 * Class FormatterTest
 * @package jones\novaposhta\tests\helpers
 */
class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \jones\novaposhta\helpers\Formatter
     */
    private $formatter;

    public function setUp()
    {
        $this->formatter = $this->getMockForTrait(Formatter::class);
    }

    /**
     * @covers \jones\novaposhta\helpers\Formatter::formatPrice()
     */
    public function testFormatPrice()
    {
        static::assertEquals('23,75', $this->formatter->formatPrice(23.75));
        static::assertEquals('15,00', $this->formatter->formatPrice(15));
        static::assertEquals('0,01', $this->formatter->formatPrice(.01));
    }

    /**
     * @covers \jones\novaposhta\helpers\Formatter::formatWeight()
     */
    public function testFormatWeight()
    {
        static::assertEquals('0,001', $this->formatter->formatWeight(.001));
        static::assertEquals('1,100', $this->formatter->formatWeight(1.1));
    }
}
