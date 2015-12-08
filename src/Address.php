<?php
namespace jones\novaposhta;

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
        return $scenarios;
    }

    /**
     * Get list of cities
     * @param string $filter
     * @return array|bool
     */
    public function getCities($filter = '')
    {
        $this->FindByString = $filter;
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
        $this->FindByString = $street;
        $this->enableValidation();
        return $this->call('getWarehouses');
    }
}
