<?php
namespace jones\novaposhta\tests\delivery;

use jones\novaposhta\delivery\Estimation;
use jones\novaposhta\models\CountPrice;
use jones\novaposhta\tests\TestCase;
use Yii;

/**
 * Class EstimationTest
 * @package jones\novaposhta\tests\delivery
 */
class EstimationTest extends TestCase
{
    /**
     * @var \jones\novaposhta\delivery\Estimation
     */
    private $estimation;

    protected function setUp()
    {
        $this->createApp();
        $requestFactory = $this->getRequestFactory();
        $this->estimation = Yii::createObject(Estimation::class, [$requestFactory]);
    }

    /**
     * @covers \jones\novaposhta\delivery\Estimation::getEstimatedDate()
     * @param $sendDate
     * @param $expectedDate
     * @dataProvider estimatedDateDataProvider
     */
    public function testGetEstimatedDate($sendDate, $expectedDate)
    {
        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'estimatedDeliveryDate' => $expectedDate
            ]);

        $date = $this->estimation->getEstimatedDate('Kiev', 'Odessa', $sendDate);
        static::assertEquals($expectedDate, $date);
    }

    /**
     * @covers \jones\novaposhta\delivery\Estimation::getPriceWithDate()
     */
    public function testGetPriceWithDate()
    {
        /** @var \jones\novaposhta\models\CountPrice $countPrice */
        $countPrice = Yii::createObject(CountPrice::class);
        $countPrice->setAttributes([
            'senderCity' => 'Kiev',
            'recipientCity' => 'Odessa',
            'mass' => 2,
            'height' => 40,
            'width' => 60,
            'depth' => 15,
            'publicPrice' => 420,
            'postpay_sum' => 25,
            'date' => '14.11.2015'
        ], false);

        $this->request->expects(static::once())
            ->method('execute')
            ->willReturn([
                'date' => '16.11.2015',
                'cost' => 25
            ]);

        $this->estimation->getPriceWithDate($countPrice);
    }

    /**
     * Get variations of dates
     * @return array
     */
    public function estimatedDateDataProvider()
    {
        return [
            ['14.11.2015', '17.11.2015'],
        ];
    }
}
