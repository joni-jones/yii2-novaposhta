<?php
namespace jones\novaposhta\tests;
use jones\novaposhta\Address;

/**
 * Class AddressTest
 */
class AddressTest extends TestCase
{
    /**
     * @var \jones\novaposhta\Address
     */
    private $model;

    protected function setUp()
    {
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
                    'item' => [
                        ['Description' => 'Kievskaya', 'Ref' => '000c2965ae0e'],
                        ['Description' => 'Odesskaya', 'Ref' => '8ejf4vb2c0e6'],
                    ]
                ],
                'info' => 'Api request info'
            ]);
        $response = $this->model->getAreas();
        static::assertEquals(2, count($response['item']));
        static::assertEquals('8ejf4vb2c0e6', $response['item'][1]['Ref']);
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
                    'item' => [
                        ['Description' => 'Kievskaya', 'Ref' => '000c2965ae0e', 'AreasCenter' => '001a92567626']
                    ]
                ],
                'warnings' => 'Filter not used'
            ]);
        $filter = 'Kiev';
        $response = $this->model->getCities($filter);
        static::assertEquals(1, count($response));
    }

    /**
     * @covers \jones\novaposhta\Address::delete
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
                    'item' => [
                        'Ref' => '005056801329'
                    ]
                ],
                'errors' => $message
            ]);
        static::assertFalse($this->model->delete($id));
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
                    'item' => [
                        'Ref' => $id
                    ]
                ]
            ]);
        static::assertTrue($this->model->delete($id));
    }
}
