<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Counterparty;

/**
 * Class CounterpartyTest
 */
class CounterpartyTest extends TestCase
{
    /**
     * @var \jones\novaposhta\Counterparty
     */
    protected $model;

    protected function setUp()
    {
        $this->createApp();
        $requestFactory = $this->getRequestFactory();
        $this->model = new Counterparty($requestFactory);
    }

    /**
     * @covers \jones\novaposhta\Counterparty::getCounterparties
     */
    public function testGetCounterparties()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    ['Ref' => '0050568046cd', 'ERDPOU' => '999999'],
                    ['Ref' => '005056887b8d', 'ERDPOU' => '888888'],
                ],
                'warnings' => [],
                'info' => []
            ]);
        static::assertEquals(2, count($this->model->getCounterparties()));
    }

    /**
     * @covers \jones\novaposhta\Counterparty::getCounterpartyAddresses
     */
    public function testGetCounterpartyAddresses()
    {
        $ref = '0050568046cd';
        $type = Counterparty::TYPE_SENDER;
        $this->request->expects(static::once())
            ->method('build')
            ->with(
                'Counterparty',
                'getCounterpartyAddresses',
                [
                    'Ref' => $ref,
                    'CounterpartyProperty' => $type
                ]
            )
            ->willReturnSelf();

        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    ['Ref' => $ref, 'Description' => 'Нова вул. 10'],
                ],
                'warnings' => [],
                'info' => []
            ]);
        static::assertEquals(1, count($this->model->getCounterpartyAddresses($ref, $type)));
    }

    /**
     * \jones\novaposhta\Counterparty::getCounterpartyContactPersons
     */
    public function testGetCounterpartyContactPersons()
    {
        $ref = '0050568046cd';
        $this->request->expects(static::once())
            ->method('build')
            ->with(
                'Counterparty',
                'getCounterpartyContactPersons',
                [
                    'Ref' => $ref,
                ]
            )
            ->willReturnSelf();

        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    ['Ref' => $ref, 'FirstName' => 'Test', 'LastName' => 'Test'],
                ],
                'warnings' => [],
                'info' => []
            ]);

        static::assertEquals(1, count($this->model->getCounterpartyContactPersons($ref)));
    }

    /**
     * @covers \jones\novaposhta\Counterparty::save
     */
    public function testSave()
    {
        $data = $this->getContractorData();
        $this->request->expects(static::once())
            ->method('build')
            ->with('Counterparty', 'save', $data)
            ->willReturnSelf();

        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Ref' => '005056801329'
                ],
                'warnings' => [],
                'info' => []
            ]);

        $this->model->setAttributes($data);
        static::assertNotEmpty($this->model->save());
    }

    /**
     * @covers \jones\novaposhta\Counterparty::update
     */
    public function testUpdate()
    {
        $data = $this->getContractorData();
        $data['Ref'] = '005056801329';

        $this->request->expects(static::once())
            ->method('build')
            ->with('Counterparty', 'update', $data)
            ->willReturnSelf();

        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => $data,
                'warnings' => [],
                'info' => []
            ]);

        $this->model->setAttributes($data);
        static::assertNotEmpty($this->model->update());
    }

    /**
     * Get contractor test details
     * @return array
     */
    protected function getContractorData()
    {
        return [
            'CityRef' => '22nan2c67462',
            'CounterpartyProperty' => 'Recipient',
            'CounterpartyType' => 'PrivatePerson',
            'Email' => 'test.contractor@test.com',
            'FirstName' => 'Ivan',
            'LastName' => 'Ivanov',
            'MiddleName' => 'Ivanovich',
            'Phone' => '0452345688'
        ];
    }
}
