<?php
namespace jones\novaposhta\converters;

use yii\base\NotSupportedException;

/**
 * Class ConverterFactory
 * @package jones\novaposhta\converters
 */
class ConverterFactory
{
    /**
     * Create format converter
     * @param string $format
     * @return JsonConverter|XmlConverter
     * @throws NotSupportedException
     */
    public function create($format = ConverterInterface::FORMAT_XML)
    {
        switch ($format) {
            case ConverterInterface::FORMAT_XML:
                return new XmlConverter();
            case ConverterInterface::FORMAT_JSON:
                return new JsonConverter();
            default:
                throw new NotSupportedException('The specified convert format not supported');
        }
    }
}
