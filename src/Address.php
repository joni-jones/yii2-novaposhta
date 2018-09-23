<?php
namespace jones\novaposhta;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Address
 * This model contain methods to work with addresses
 *
 * @method array getAreas()
 * @method array getWarehouseTypes()
 */
final class Address extends Api
{
    const SCENARIO_WAREHOUSE = 'warehouse';

    const SCENARIO_GET_CITIES = 'getCities';

    /**
     * Unique identifier of city
     * @var string
     */
    public $CityRef;

    /**
     * Contractor id
     * @var string
     */
    public $CounterpartyRef;

    /**
     * Number of building
     * @var string
     */
    public $BuildingNumber;

    /**
     * Number of flat
     * @var int
     */
    public $Flat;

    /**
     * Additional comment
     * @var string
     */
    public $Note;

    /**
     * Street id
     * @var string
     */
    public $StreetRef;

    /**
     * List of available address api methods
     * @var array
     */
    protected $methods = [
        'getAreas', 'getWarehouseTypes'
    ];

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        $rules = parent::rules();
        return ArrayHelper::merge($rules, [
            [['FindByString', 'Ref', 'CityRef', 'CounterpartyRef', 'BuildingNumber', 'Note', 'StreetRef'], 'string'],
            [['Flat'], 'integer'],
            ['CityRef', 'required', 'on' => self::SCENARIO_WAREHOUSE],
            [['CounterpartyRef', 'StreetRef', 'BuildingNumber'], 'required', 'on' => self::SCENARIO_SAVE],
            [['CounterpartyRef', 'BuildingNumber', 'Ref'], 'required', 'on' => self::SCENARIO_UPDATE],
        ]);
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_WAREHOUSE] = ['CityRef', 'FindByString'];
        $scenarios[self::SCENARIO_GET_CITIES] = ['Ref', 'FindByString'];
        $scenarios[self::SCENARIO_SAVE] = ['BuildingNumber', 'CounterpartyRef', 'Flat', 'Note', 'StreetRef'];
        $scenarios[self::SCENARIO_UPDATE] = ['BuildingNumber', 'CounterpartyRef', 'Flat', 'Note', 'StreetRef', 'Ref'];
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
            'CounterpartyRef' => Yii::t('api', 'Counterparty Ref'),
            'StreetRef' => Yii::t('api', 'Street Ref'),
            'BuildingNumber' => Yii::t('api', 'Building Number'),
            'Flat' => Yii::t('api', 'Flat'),
            'Note' => Yii::t('api', 'Note'),
        ];
    }

    protected static $_areas = null;
    /**
     * Get list of cities
     * @param string $filter
     * @return array|bool
     */
    public function getArea($filter = '')
    {
        if (self::$_areas === null)
            self::$_areas = $this->getAreas();

        foreach(self::$_areas as $area)
            if ($filter == $area['Description'])
                return $area;

        return null;
    }

    /**
     * Get list of cities
     * @param string $filter
     * @return array|bool
     */
    public function getCities($filter = '')
    {
        $this->addFilter($filter);
        return $this->call(self::SCENARIO_GET_CITIES);
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
     * Get city streets
     * @param string $cityRef
     * @param string $title
     * @return array|bool
     */
    public function getStreet($cityRef, $title = '')
    {
        $this->setScenario(self::SCENARIO_WAREHOUSE);
        $this->CityRef = $cityRef;
        $this->addFilter($title);
        $this->enableValidation();
        return $this->call('getStreet');
    }

    /**
     * Save new address
     * @param string $building
     * @param int $flat
     * @param string $comment
     * @return array|bool
     */
    public function save($building, $flat = null, $comment = null)
    {
        return $this->saveAddress(self::SCENARIO_SAVE, $building, $flat, $comment);
    }

    /**
     * Update exists address
     * @param string $building
     * @param int $flat
     * @param string $comment
     * @return array|bool
     */
    public function update($building, $flat = null, $comment = null)
    {
        return $this->saveAddress(self::SCENARIO_UPDATE, $building, $flat, $comment);
    }

    /**
     * Save address details
     * @param string $method
     * @param string $building
     * @param int $flat
     * @param string $comment
     * @return array|bool
     */
    protected function saveAddress($method, $building, $flat, $comment)
    {
        $this->setScenario($method);
        $this->enableValidation();
        $this->BuildingNumber = $building;
        $this->Flat = $flat;
        $this->Note = $comment;
        return $this->call($method);
    }
}
