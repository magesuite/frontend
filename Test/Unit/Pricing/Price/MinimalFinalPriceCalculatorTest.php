<?php

namespace MageSuite\Frontend\Test\Unit\Pricing\Price;

use MageSuite\Frontend\Pricing\Price\MinimalFinalPriceCalculator;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\PriceInfoInterface;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;

class MinimalFinalPriceCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var MinimalFinalPriceCalculator
     */
    private $minimalFinalPriceCalculator;

    /**
     * @var SaleableInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $saleable;

    /**
     * @var PriceInfoInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceInfo;

    /**
     * @var FinalPrice|\PHPUnit_Framework_MockObject_MockObject
     */
    private $finalPrice;

    /**
     * @var CalculatorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $calculator;

    public function setUp(): void
    {
        $this->finalPrice = $this->getMockBuilder(FinalPrice::class)->disableOriginalConstructor()->getMock();
        $this->priceInfo = $this->createMock(PriceInfoInterface::class);
        $this->saleable = $this->createMock(SaleableInterface::class);

        $this->objectManager = new ObjectManager($this);

        $this->calculator = $this->createMock(CalculatorInterface::class);
        $this->minimalFinalPriceCalculator = $this->objectManager->getObject(
            MinimalFinalPriceCalculator::class,
            ['calculator' => $this->calculator]
        );
    }

    private function getMinValueAndPrepareMock()
    {
        $minPrice = 5;

        $minAmount = $this->createMock(AmountInterface::class);
        $minAmount->expects($this->once())->method('getValue')->willReturn($minPrice);
        $this->finalPrice->expects($this->once())->method('getMinimalPrice')->willReturn($minAmount);
        $this->priceInfo->expects($this->once())->method('getPrice')->with(FinalPrice::PRICE_CODE)->willReturn($this->finalPrice);
        $this->saleable->expects($this->once())->method('getPriceInfo')->willReturn($this->priceInfo);

        return $minPrice;
    }

    public function testGetValueShouldReturnMinPrice()
    {
        $minPrice = $this->getMinValueAndPrepareMock();
        $this->assertEquals($minPrice, $this->minimalFinalPriceCalculator->getValue($this->saleable));
    }

    public function testGetAmountShouldReturnAmountObject()
    {
        $minPrice = $this->getMinValueAndPrepareMock();

        $amount = $this->createMock(AmountInterface::class);

        $this->calculator->expects($this->once())
            ->method('getAmount')
            ->with($minPrice, $this->saleable)
            ->willReturn($amount);

        $this->assertSame($amount, $this->minimalFinalPriceCalculator->getAmount($this->saleable));
    }
}
