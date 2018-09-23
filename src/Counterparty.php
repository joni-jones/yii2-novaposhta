<?php
namespace jones\novaposhta;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Counterparty
 * 
 * This model contain methods to work with contractor data
 */
final class Counterparty extends Api
{
    const TYPE_SENDER = 'Sender';

    const TYPE_RECIPIENT = 'Recipient';

    const COUNTERPARTY_TYPE_PRIVATE_PERSON = 'PrivatePerson';

    const COUNTERPARTY_TYPE_ORG = 'Organization';

    const SCENARIO_GET_COUNTERPARTIES = 'getCounterparties';

    const SCENARIO_GET_ADDRESSES = 'getCounterpartyAddresses';

    const SCENARIO_GET_CONTACT_PERSONS = 'getCounterpartyContactPersons';

    /**
     * Type of counterparty
     * @var string
     */
    public $CounterpartyProperty;

    /**
     * ID of city
     * @var string
     */
    public $CityRef;

    /**
     * Type of contractor
     * @var string
     */
    public $CounterpartyType;

    /**
     * Contractor email
     * @var string
     */
    public $Email;

    /**
     * Contractor first name
     * @var string
     */
    public $FirstName;

    /**
     * Contractor last name
     * @var string
     */
    public $LastName;

    /**
     * Contractor middle name
     * @var string
     */
    public $MiddleName;

    /**
     * Contractor phone
     * @var string
     */
    public $Phone;

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        $rules = parent::rules();
        return ArrayHelper::merge($rules, [
            [
                [
                    'CounterpartyProperty', 'FindByString', 'Ref', 'CityRef', 'CounterpartyType', 'FirstName',
                    'LastName', 'MiddleName', 'Email', 'Phone'
                ],
                'string'
            ],
            ['Email', 'email'],
            ['CounterpartyProperty', 'required', 'on' => self::SCENARIO_GET_COUNTERPARTIES],
            ['Counterparty', 'in', 'range' => array_keys($this->getCounterpartyTypes())],
            [['Ref', 'CounterpartyProperty'], 'required', 'on' => self::SCENARIO_GET_ADDRESSES],
            ['Ref', 'required', 'on' => self::SCENARIO_GET_CONTACT_PERSONS],
            [
                [
                    'CityRef', 'CounterpartyProperty', 'CounterpartyType', 'FirstName', 'LastName',
                    'Phone'
                ],
                'required', 'on' => self::SCENARIO_SAVE
            ],
            [
                ['FirstName', 'LastName', 'MiddleName', 'Phone', 'Ref'],
                'required', 'on' => self::SCENARIO_UPDATE
            ],
        ]);
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_GET_COUNTERPARTIES] = ['CounterpartyProperty', 'FindByString'];
        $scenarios[self::SCENARIO_GET_ADDRESSES] = ['Ref', 'CounterpartyProperty'];
        $scenarios[self::SCENARIO_GET_CONTACT_PERSONS] = ['Ref'];
        $scenarios[self::SCENARIO_SAVE] = [
            'CityRef', 'CounterpatyType', 'CounterpartyProperty', 'Email', 'FirstName',
            'LastName', 'MiddleName', 'Phone'
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'CityRef', 'CounterpatyType', 'CounterpartyProperty', 'Email', 'FirstName',
            'LastName', 'MiddleName', 'Phone', 'Ref'
        ];
        return $scenarios;
    }

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function getAttributeLabels()
    {
        return [
            'CounterpartyProperty' => Yii::t('api', 'Counterparty Property'),
            'Ref' => Yii::t('api', 'Ref'),
            'CounterpatyType' => Yii::t('api', 'Counterparty Type'),
            'CityRef' => Yii::t('api', 'City Ref'),
            'Email' => Yii::t('api', 'Email'),
            'FirstName' => Yii::t('api', 'First Name'),
            'LastName' => Yii::t('api', 'Last Name'),
            'MiddleName' => Yii::t('api', 'Middle Name'),
            'Phone' => Yii::t('api', 'Phone'),
        ];
    }

    /**
     * Get list of available counterparty types
     * @return array
     */
    public function getCounterpartyTypes()
    {
         return [
             self::TYPE_RECIPIENT => Yii::t('api', 'Recipient'),
             self::TYPE_SENDER => Yii::t('api', 'Sender')
         ];
    }

    /**
     * Get list of counterparties
     * @param string $type
     * @param string $filter
     * @return array|bool
     */
    public function getCounterparties($type = self::TYPE_SENDER, $filter = '')
    {
        $this->setScenario(self::SCENARIO_GET_COUNTERPARTIES);
        $this->enableValidation();
        $this->CounterpartyProperty = $type;
        $this->addFilter($filter);
        return $this->call(self::SCENARIO_GET_COUNTERPARTIES);
    }

    /**
     * Get list of counterparty addresses
     * @param string $contractor
     * @param string $type
     * @return array|bool
     */
    public function getCounterpartyAddresses($contractor, $type = self::TYPE_SENDER)
    {
        $this->setScenario(self::SCENARIO_GET_ADDRESSES);
        $this->enableValidation();
        $this->Ref = $contractor;
        $this->CounterpartyProperty = $type;
        return $this->call(self::SCENARIO_GET_ADDRESSES);
    }

    /**
     * Get list of contact persons
     * @param string $contractor
     * @return array|bool
     */
    public function getCounterpartyContactPersons($contractor)
    {
        $this->setScenario(self::SCENARIO_GET_CONTACT_PERSONS);
        $this->enableValidation();
        $this->Ref = $contractor;
        return $this->call(self::SCENARIO_GET_CONTACT_PERSONS);
    }

    /**
     * Create new contractor
     * @return array|bool
     */
    public function save()
    {
        return $this->saveCounterparty(self::SCENARIO_SAVE);
    }

    /**
     * Update contractor details
     * @return array|bool
     */
    public function update()
    {
        return $this->saveCounterparty(self::SCENARIO_UPDATE);
    }

    /**
     * Save contractor details
     * @param string $method
     * @return array|bool
     */
    protected function saveCounterparty($method)
    {
        $this->setScenario($method);
        $this->enableValidation();
        return $this->call($method);
    }
}
