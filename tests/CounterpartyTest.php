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
}
