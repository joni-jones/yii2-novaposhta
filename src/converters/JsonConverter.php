<?php
namespace jones\novaposhta\converters;

use yii\helpers\Json;

/**
 * Class JsonConverter
 * @package jones\novaposhta\converters
 */
class JsonConverter implements ConverterInterface
{
    const CONTENT_TYPE = 'application/json';

    /**
     * @inheritdoc
     */
    public function encode(array $data)
    {
        return Json::encode($data);
    }

    /**
     * @inheritdoc
     */
    public function decode($data)
    {
        return Json::decode($data);
    }

    /**
     * @inheritdoc
     */
    public function getContentType()
    {
        return self::CONTENT_TYPE;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return ConverterInterface::FORMAT_JSON;
    }
}
