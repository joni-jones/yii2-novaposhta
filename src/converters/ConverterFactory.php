<?php

namespace jones\novaposhta\converters;
use yii\base\NotSupportedException;

/**
 * Class ConverterFactory
 * @package jones\novaposhta\converters
 */
class ConverterFactory
{
    const FORMAT_JSON = 'json';

    const FORMAT_XML = 'xml';

    /**
     * Create format converter
     * @param string $format
     * @return JsonConverter|XmlConverter
     * @throws NotSupportedException
     */
    public function create($format = self::FORMAT_XML)
    {
        switch ($format) {
            case self::FORMAT_XML:
                return new XmlConverter();
            case self::FORMAT_JSON:
                return new JsonConverter();
            default:
                throw new NotSupportedException('The specified convert format not supported');
        }
    }
}
