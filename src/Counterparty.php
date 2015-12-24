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

    const SCENARIO_GET_COUNTERPARTIES = 'getCounterparties';

    const SCENARIO_GET_ADDRESSES = 'getCounterpartyAddresses';

    const SCENARIO_GET_CONTACT_PERSONS = 'getCounterpartyContactPersons';

    /**
     * Type of counterparty
     * @var string
     */
    public $CounterpartyProperty;

    /**
     * @inheritdoc
     * @codeCoverageIgnore
     */
    public function rules()
    {
        $rules = parent::rules();
        return ArrayHelper::merge($rules, [
            [['CounterpartyProperty', 'FindByString', 'Ref'], 'string'],
            ['CounterpartyProperty', 'required', 'on' => self::SCENARIO_GET_COUNTERPARTIES],
            ['Counterparty', 'in', 'range' => array_keys($this->getCounterpartyTypes())],
            [['Ref', 'CounterpartyProperty'], 'required', 'on' => self::SCENARIO_GET_ADDRESSES],
            ['Ref', 'required', 'on' => self::SCENARIO_GET_CONTACT_PERSONS],
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
}
