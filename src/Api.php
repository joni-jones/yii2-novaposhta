<?php
namespace jones\novaposhta;

use jones\novaposhta\http\ClientException;
use jones\novaposhta\request\RequestFactory;
use Yii;
use yii\base\Model;

/**
 * Class Api
 */
class Api extends Model
{
    const SCENARIO_UPDATE = 'update';

    const SCENARIO_DELETE = 'delete';

    const SCENARIO_SAVE = 'save';

    /**
     * Unique identifier
     * @var string
     */
    public $Ref;

    /**
     * Filter for request
     * @var string
     */
    public $FindByString;

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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['Ref', 'required', 'on' => self::SCENARIO_DELETE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DELETE] = ['Ref'];
        return $scenarios;
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->setScenario(self::SCENARIO_DELETE);
        $this->enableValidation();
        return (boolean) $this->call(self::SCENARIO_DELETE);
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
            $class = new \ReflectionClass($this);
            $request->build($class->getShortName(), $method, $this->getValues());
            $response = $request->execute();
        } catch (ClientException $e) {
            $this->addError($method, $e->getMessage());
            return false;
        }

        $success = filter_var($response['success'], FILTER_VALIDATE_BOOLEAN);
        if (!$success) {
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

    /**
     * Get list of init attributes
     * @return array
     */
    private function getValues()
    {
        $values = [];
        $attributes = $this->attributes();
        foreach ($attributes as $name) {
            if (empty($this->$name)) {
                continue;
            }
            $values[$name] = $this->$name;
        }
        return $values;
    }

    /**
     * Add filter
     * @param string $filter
     */
    protected function addFilter($filter)
    {
        $this->FindByString = $filter;
    }
}
