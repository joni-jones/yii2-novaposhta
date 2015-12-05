<?php
namespace jones\novaposhta;

use jones\novaposhta\http\ClientException;
use jones\novaposhta\request\RequestFactory;
use Yii;
use yii\base\Model;

/**
 * Class Api
 * @package jones\novaposhta
 */
class Api extends Model
{
    /**
     * Enable validation for model attributes
     * @var boolean
     */
    protected $validationEnabled = false;

    /**
     * List of api methods
     * @var array
     */
    protected $methods = [];

    /**
     * Factory to create request object
     * @var \jones\novaposhta\request\RequestFactory;
     */
    private $requestFactory;

    /**
     * Init api model
     * @param RequestFactory $factory
     */
    public function __construct(RequestFactory $factory)
    {
        $this->requestFactory = $factory;
    }

    /**
     * Call specified api model method or default of base model
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        if (in_array($name, $this->methods)) {
            return $this->call($name);
        }
        return parent::__call($name, $params);
    }

    /**
     * Call api method
     * @param string $method name of api method
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function call($method)
    {
        if ($this->validationEnabled && !$this->validate()) {
            return false;
        }
        $request = $this->requestFactory->create();
        try {
            $attributes = $this->attributes();
            $class = new \ReflectionClass($this);
            $request->build($class->getShortName(), $method, $this->getAttributes($attributes));
            $response = $request->execute();
        } catch (ClientException $e) {
            $this->addError($method, $e->getMessage());
            return false;
        }
        if (!(boolean) $response['success']) {
            $this->addError($method, (string) $response['errors']);
            return false;
        }
        if (!empty($response['warnings'])) {
            Yii::warning((string) $response['warnings']);
        }
        if (!empty($response['info'])) {
            Yii::info($response['info']);
        }
        return (array) $response['data'];
    }
}