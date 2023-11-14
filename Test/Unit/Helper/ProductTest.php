<?php

namespace MageSuite\Frontend\Test\Unit\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class ProductTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;
    /**
     * @var \MageSuite\Frontend\Helper\Product
     */
    private $productHelper;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->productHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Product::class);
    }

    public static function getDates()
    {
        return [
            [false, false, '2017-09-08', false],
            ['2017-09-07', false, '2017-09-08', true],
            ['2017-09-09', false, '2017-09-08', false],
            [false, '2017-09-09', '2017-09-08', true],
            [false, '2017-09-07', '2017-09-08', false],
            ['2017-09-07', '2017-09-09', '2017-09-08', true],
            ['2017-09-06', '2017-09-07', '2017-09-08', false]
        ];
    }

    /**
     * @dataProvider getDates
     * @param $fromDate
     * @param $toDate
     * @param $date
     * @param $expected
     */
    public function testItReturnsIsNew($fromDate, $toDate, $date, $expected)
    {
        $productStub = $this->prepareProductStubForIsNew($fromDate, $toDate);

        $this->assertEquals($expected, $this->productHelper->isNew($productStub, $date));
    }


    protected function prepareProductStubForIsNew($fromDate, $toDate)
    {
        /** @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject $productStub */
        $productStub = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getNewsFromDate', 'getNewsToDate'])
            ->getMock();

        $productStub->method('getNewsFromDate')->willReturn($fromDate);
        $productStub->method('getNewsToDate')->willReturn($toDate);

        return $productStub;
    }

    /**
     * @dataProvider getOnSaleDates
     * @param $specialPrice
     * @param $specialPriceFrom
     * @param $specialPriceTo
     * @param $getPrice
     * @param $getFinalPrice
     * @param $expected
     */
    public function testItReturnsIsOnSale($specialPrice, $specialPriceFrom, $specialPriceTo, $getPrice, $getFinalPrice, $expected)
    {
        $productStub = $this->prepareProductStubForOnSale($specialPrice, $specialPriceFrom, $specialPriceTo, $getPrice, $getFinalPrice);

        $this->assertEquals($expected, $this->productHelper->isOnSale($productStub));
    }


    protected function prepareProductStubForOnSale($specialPrice, $specialPriceFrom, $specialPriceTo, $getPrice, $getFinalPrice)
    {
        /** @var \Magento\Catalog\Model\Product|\PHPUnit_Framework_MockObject_MockObject $productStub */
        $productStub = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSpecialPrice', 'getSpecialFromDate', 'getSpecialToDate', 'getPrice', 'getFinalPrice', 'getTypeId'])
            ->getMock();

        $productStub->method('getSpecialPrice')->willReturn($specialPrice);
        $productStub->method('getSpecialFromDate')->willReturn($specialPriceFrom);
        $productStub->method('getSpecialToDate')->willReturn($specialPriceTo);
        $productStub->method('getPrice')->willReturn($getPrice);
        $productStub->method('getFinalPrice')->willReturn($getFinalPrice);
        $productStub->method('getTypeId')->willReturn('simple');

        return $productStub;
    }

    public static function getOnSaleDates()
    {
        return [
            [100, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('+7 days')), 200, 100, true],
            [100, date('Y-m-d 00:00:00', strtotime('+7 days')), date('Y-m-d 00:00:00', strtotime('+17 days')), 200, 100, false],
            [100, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, false],
            [300, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, false],
            ['', date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, false],
            [100, null, date('Y-m-d 00:00:00', strtotime('+3 days')), 200, 100, true],
            [100, null, date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, false],
            [100, date('Y-m-d 00:00:00', strtotime('-3 days')), null, 200, 100, true],
            ['', null, null, 200, 100, false],
            [100, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('+7 days')), 0, 100, false]
        ];
    }

    /**
     * @dataProvider getPercentage
     * @param $specialPrice
     * @param $specialPriceFrom
     * @param $specialPriceTo
     * @param $getPrice
     * @param $getFinalPrice
     * @param $customFinalPrice
     * @param $expected
     */
    public function testItReturnsCorrectPercentage($specialPrice, $specialPriceFrom, $specialPriceTo, $getPrice, $getFinalPrice, $customFinalPrice, $expected)
    {
        $productStub = $this->prepareProductStubForOnSale($specialPrice, $specialPriceFrom, $specialPriceTo, $getPrice, $getFinalPrice);

        $this->assertEquals($expected, $this->productHelper->getSalePercentage($productStub, $customFinalPrice));
    }

    public static function getPercentage()
    {
        return [
            [100, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('+7 days')), 200, 100, null, 50],
            [100, date('Y-m-d 00:00:00', strtotime('+7 days')), date('Y-m-d 00:00:00', strtotime('+17 days')), 200, 100, null, false],
            [100, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, null, false],
            [300, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, null, false],
            ['', date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, null, false],
            [100, null, date('Y-m-d 00:00:00', strtotime('+3 days')), 500, 150, null, 70],
            [100, null, date('Y-m-d 00:00:00', strtotime('-3 days')), 200, 100, null, false],
            [100, date('Y-m-d 00:00:00', strtotime('-3 days')), null, 300, 10, null, 97],
            ['',null, null, 200, 100, null, false],
            ['',null, null, 200, 100, 50, 75],
            [100, date('Y-m-d 00:00:00', strtotime('-7 days')), date('Y-m-d 00:00:00', strtotime('+7 days')), 200, 100, 50, 75],
        ];

    }
}
