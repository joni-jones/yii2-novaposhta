<?php
namespace jones\novaposhta\tests;

use jones\novaposhta\Address;
use jones\novaposhta\http\ClientException;

/**
 * Class AddressTest
 */
class AddressTest extends TestCase
{

    /**
     * @var \jones\novaposhta\Address
     */
    protected $model;

    protected function setUp()
    {
        $this->createApp();
        $requestFactory = $this->getRequestFactory();
        $this->model = new Address($requestFactory);
    }

    public function testGetAreas()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => 'true',
                'data' => [
                    ['Description' => 'Kievskaya', 'Ref' => '000c2965ae0e'],
                    ['Description' => 'Odesskaya', 'Ref' => '8ejf4vb2c0e6'],
                ],
                'warnings' => '',
                'info' => 'Api request info'
            ]);
        $response = $this->model->getAreas();
        static::assertEquals(2, count($response));
        static::assertEquals('8ejf4vb2c0e6', $response[1]['Ref']);
    }

    /**
     * @covers \jones\novaposhta\Address::getCities
     */
    public function testGetCities()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    ['Description' => 'Kievskaya', 'Ref' => '000c2965ae0e', 'AreasCenter' => '001a92567626']
                ],
                'warnings' => [],
                'info' => []
            ]);
        $filter = 'Kiev';
        $response = $this->model->getCities($filter);
        static::assertEquals(1, count($response));
    }

    /**
     * @covers \jones\novaposhta\Address::call
     */
    public function testGetCitiesWithException()
    {
        $message = 'Test Exception';
        $exception = new ClientException($message);
        $this->request->expects(static::once())
            ->method('execute')
            ->willThrowException($exception);

        static::assertFalse($this->model->getCities());
        static::assertEquals($message, $this->model->getFirstError('getCities'));
    }

    /**
     * @covers \jones\novaposhta\Api::enableValidation
     */
    public function testEnableValidation()
    {
        $this->model->Ref = '100c9b0023m';
        $this->model->delete();
        static::assertTrue($this->model->isAttributeRequired('Ref'));
    }

    /**
     * @covers \jones\novaposhta\Address::call
     */
    public function testDeleteWithFailedValidation()
    {
        $this->request->expects(static::never())
            ->method('build');
        $this->request->expects(static::never())
            ->method('execute');
        static::assertFalse($this->model->delete());
        static::assertEquals('Ref cannot be blank.', $this->model->getFirstError('Ref'));
    }

    /**
     * @covers \jones\novaposhta\Address::call
     */
    public function testUnSuccessDelete()
    {
        $id = '005056801329';
        $message = 'Address not deleted';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => false,
                'data' => [
                    'Ref' => '005056801329'
                ],
                'errors' => [$message]
            ]);
        $this->model->Ref = $id;
        static::assertFalse($this->model->delete());
        static::assertEquals($message, $this->model->getFirstError('delete'));
    }

    /**
     * @covers \jones\novaposhta\Address::delete
     */
    public function testDelete()
    {
        $id = '0025909b4e33';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Ref' => $id
                ],
                'warnings' => '',
                'info' => '',
            ]);
        $this->model->Ref = $id;
        static::assertTrue($this->model->delete());
    }

    /**
     * @covers \jones\novaposhta\Address::logWarnings
     */
    public function testLogWarnings()
    {
        $id = 'cb25d39b4em2';
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Ref' => $id
                ],
                'warnings' => 'Contractor address can\'t be deleted',
                'info' => '',
            ]);
        $this->model->Ref = $id;
        static::assertTrue($this->model->delete());
    }

    /**
     * @covers \jones\novaposhta\Address::getWarehouses
     */
    public function testGetWarehouses()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Description' => 'Відділення №1',
                    'DescriptionRu' => 'Отделение №1',
                ],
                'info' => '',
                'warnings' => ''
            ]);

        $this->model->CityRef = '001a92567626';
        $this->model->getWarehouses();
    }

    /**
     * @covers \jones\novaposhta\Address::getValues
     */
    public function testGetCityWarehouses()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Ref' => '0050568002cf',
                    'Number' => 1
                ],
                'info' => '',
                'warnings' => ''
            ]);
        $this->model->CityRef = 'd4ae527baec9';
        static::assertTrue(is_array($this->model->getWarehouses('Киевская')));
    }

    /**
     * @covers \jones\novaposhta\Address::getStreet
     */
    public function testGetStreet()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [],
                'info' => '',
                'warnings' => ''
            ]);
        static::assertTrue(is_array($this->model->getStreet('001a92567626', '1-')));
    }

    public function testGetWarehouseTypes()
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    ['Description' => 'Parcel Shop'],
                    ['Description' => 'Поштовевідділення'],
                    ['Description' => 'Поштоматприватбанку'],
                    ['Description' => 'Вантажневідділення'],
                    ['Description' => 'Поштомат'],
                ],
                'info' => '',
                'warnings' => ''
            ]);

        $response = $this->model->getWarehouseTypes();
        static::assertTrue(is_array($response));
        static::assertEquals(5, count($response));
    }

    /**
     * @covers \jones\novaposhta\Address::save
     */
    public function testSaveWithFailedValidation()
    {
        $this->flushAttributes();
        $this->request->expects(static::never())
            ->method('execute');

        $response = $this->model->save('10', 24);
        static::assertFalse($response);
        static::assertEquals(2, count($this->model->getErrors()));
        static::assertEquals('Counterparty Ref cannot be blank.', $this->model->getFirstError('CounterpartyRef'));
        static::assertEquals('Street Ref cannot be blank.', $this->model->getFirstError('StreetRef'));
    }

    /**
     * @covers \jones\novaposhta\Address::update
     */
    public function testUpdateFailedValidation()
    {
        $this->flushAttributes();
        $this->request->expects(static::never())
            ->method('execute');

        static::assertFalse($this->model->update('25', 12));
        static::assertEquals(2, count($this->model->getErrors()));
        static::assertEquals('Counterparty Ref cannot be blank.', $this->model->getFirstError('CounterpartyRef'));
        static::assertEquals('Ref cannot be blank.', $this->model->getFirstError('Ref'));
    }

    /**
     * @covers \jones\novaposhta\Address::update
     */
    public function testUpdate()
    {
        $this->flushAttributes();
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Ref' => '503702df-cd4c-11e4-bdb5-005056801329',
                    'Description' => 'Антонова вул. 23/12'
                ],
                'warnings' => [],
                'info' => []
            ]);

        $this->model->Ref = '503702df-cd4c-11e4-bdb5-005056801329';
        $this->model->CounterpartyRef = '5953fb16-08d8-11e4-8958-0025909b4e33';

        $response = $this->model->update('23/12');
        static::assertTrue(is_array($response));
    }

    /**
     * @covers \jones\novaposhta\Address::saveAddress
     */
    public function testSaveAddress()
    {
        $this->flushAttributes();
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'success' => true,
                'data' => [
                    'Ref' => '473188f3-d847-11e4-bdb5-005056801329',
                    'Description' => 'Бердичівська вул. 10 кв. 15'
                ],
                'warnings' => ['Building already exists'],
                'info' => []
            ]);

        $this->model->StreetRef = 'd8364179-4149-11dd-9198-001d60451983';
        $this->model->CounterpartyRef = '56300fb9-cbd3-11e4-bdb5-005056801329';

        static::assertTrue(is_array($this->model->save('10', 15)));
    }

    /**
     * @covers \jones\novaposhta\Address::addFilter
     */
    public function testAddFilter()
    {
        $street = 'Lugova';
        $this->model->getWarehouses($street);
        static::assertEquals($street, $this->model->FindByString);
    }
}
