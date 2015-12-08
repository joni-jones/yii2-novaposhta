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
     * Enable model properties validation
     */
    protected function enableValidation()
    {
        $this->validationEnabled = true;
    }

    /**
     * Check if model validation enabled
     * @return bool
     */
    protected function isValidationEnabled()
    {
        return (boolean) $this->validationEnabled;
    }

    /**
     * Call api method
     * @param string $method name of api method
     * @return array|bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function call($method)
    {
        if ($this->isValidationEnabled() && !$this->validate()) {
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
            $this->addErrors([$method => $response['errors']]);
            return false;
        }
        $this->logWarnings($response['warnings']);
        $this->logInfo($response['info']);
        return (array) $response['data'];
    }

    /**
     * Log response warnings
     * @param string $warnings
     */
    private function logWarnings($warnings)
    {
        if (!empty($warnings)) {
            Yii::warning($warnings);
        }
    }

    /**
     * Log response info
     * @param string $info
     */
    private function logInfo($info)
    {
        if (!empty($info)) {
            Yii::info($info);
        }
    }
}
