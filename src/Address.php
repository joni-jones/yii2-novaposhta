<?php
namespace jones\novaposhta;

use Yii;

/**
 * Class Address
 * This model contain methods to work with addresses
 *
 * @method array getAreas()
 */
final class Address extends Api
{
    const SCENARIO_UPDATE = 'update';

    const SCENARIO_DELETE = 'delete';

    const SCENARIO_WAREHOUSE = 'warehouse';

    const SCENARIO_GET_CITIES = 'get_cities';

    /**
     * Filter for request
     * @var string
     */
    public $FindByString;

    /**
     * Unique identifier
     * @var string
     */
    public $Ref;

    /**
     * Unique identifier of city
     * @var string
     */
    public $CityRef;

    /**
     * List of available address api methods
     * @var array
     */
    protected $methods = [
        'getAreas'
    ];

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
            [['FindByString', 'Ref', 'CityRef'], 'string'],
            ['Ref', 'required', 'on' => self::SCENARIO_DELETE],
            ['CityRef', 'required', 'on' => self::SCENARIO_WAREHOUSE],
        ];
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DELETE] = ['Ref'];
        $scenarios[self::SCENARIO_WAREHOUSE] = ['CityRef', 'FindByString'];
        $scenarios[self::SCENARIO_GET_CITIES] = ['Ref', 'FindByString'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function attributeLabels()
    {
        return [
            'FindByString' =>  Yii::t('api', 'Find By String'),
            'Ref' => Yii::t('api', 'Ref'),
            'CityRef' => Yii::t('api', 'City Ref'),
        ];
    }

    /**
     * Get list of cities
     * @param string $filter
     * @return array|bool
     */
    public function getCities($filter = '')
    {
        $this->addFilter($filter);
        return $this->call('getCities');
    }

    /**
     * Delete contractor address
     */
    public function delete()
    {
        $this->setScenario(self::SCENARIO_DELETE);
        $this->enableValidation();
        return (boolean) $this->call('delete');
    }

    /**
     * Get list of city streets
     * @param string $street
     * @return array|boolean
     */
    public function getWarehouses($street = '')
    {
        $this->setScenario(self::SCENARIO_WAREHOUSE);
        $this->addFilter($street);
        $this->enableValidation();
        return $this->call('getWarehouses');
    }

    /**
     * Add filter
     * @param string $filter
     */
    protected function addFilter($filter)
    {
        if (!empty($filter)) {
            $this->FindByString = $filter;
        }
    }
}
