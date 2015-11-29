<?php
namespace jones\novaposhta;

use jones\novaposhta\http\ClientException;
use jones\novaposhta\request\RequestFactory;
use yii\base\Model;

/**
 * Class Api
 * @package jones\novaposhta
 */
class Api extends Model
{
    /**
     * Factory to create request object
     * @var \jones\novaposhta\request\RequestFactory;
     */
    private $requestFactory;

    /**
     * Data model
     * @var \yii\base\Model
     */
    protected $model;

    /**
     * Init api model
     * @param Model $model
     * @param RequestFactory $factory
     */
    public function __construct(Model $model, RequestFactory $factory)
    {
        $this->model = $model;
        $this->requestFactory = $factory;
    }

    /**
     * Call api method
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function call()
    {
        if (!$this->model->validate()) {
            $this->addErrors($this->model->getErrors());
            return false;
        }
        $request = $this->requestFactory->create();
        try {
            $request->build(get_called_class(), __METHOD__, $this->attributes());
            $response = $request->execute();
        } catch (ClientException $e) {
            $this->addError($e->getMessage());
            return false;
        }
        if (!(boolean) $response['success']) {
            $this->addError((string) $response['errors']);
            return false;
        }
        return (array) $response['data'];
    }
}