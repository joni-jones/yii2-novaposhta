<?php
namespace jones\novaposhta;
use yii\helpers\ArrayHelper;

/**
 * Class ContactPerson
 * @package jones\novaposhta
 * @author sergii gamaiunov <hello@webkadabra.com>
 * 
 * This model contain methods to work with contact person data
 */
final class ContactPerson extends Api
{
    /**
     * @var string
     */
    public $CounterpartyRef;

    /**
     * @var string
     */
    public $FirstName;

    /**
     * @var string
     */
    public $LastName;

    /**
     * @var string
     */
    public $MiddleName;

    /**
     * @var string
     */
    public $Phone;

    /**
     * @var string
     */
    public $Email;

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        $rules = parent::rules();
        return ArrayHelper::merge($rules, [
            [['CounterpartyRef', 'FirstName', 'LastName', 'MiddleName', 'Email', 'Phone'], 'string'],
            ['Email', 'email'],
            [
                ['CounterpartyRef', 'FirstName', 'LastName', 'Phone'],
                'required', 'on' => self::SCENARIO_SAVE
            ],
            [
                ['CounterpartyRef', 'Ref', 'FirstName', 'LastName', 'Phone'],
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
        $scenarios[self::SCENARIO_SAVE] = [
            'CounterpartyRef', 'FirstName', 'LastName', 'MiddleName', 'Email', 'Phone'
        ];
        $scenarios[self::SCENARIO_UPDATE] = [
            'Ref', 'CounterpartyRef', 'FirstName', 'LastName', 'MiddleName', 'Email', 'Phone'
        ];
        return $scenarios;
    }

    public function getCounterpartyContactPerson() {
        return $this->call('getCounterpartyContactPerson');
    }

    /**
     * Create new contractor
     * @return array|bool
     */
    public function save()
    {
        return $this->saveContactPerson(self::SCENARIO_SAVE);
    }

    /**
     * Update contractor details
     * @return array|bool
     */
    public function update()
    {
        return $this->saveContactPerson(self::SCENARIO_UPDATE);
    }

    /**
     * Save contractor details
     * @param string $method
     * @return array|bool
     */
    protected function saveContactPerson($method)
    {
        $this->setScenario($method);
        $this->enableValidation();
        return $this->call($method);
    }
}