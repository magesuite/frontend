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

    public function setUp(): void
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
        $productStub = $this->prepareProductForIsNew($fromDate, $toDate);

        $this->assertEquals($expected, $this->productHelper->isNew($productStub, $date));
    }


    protected function prepareProductForIsNew($fromDate, $toDate)
    {
        $product = $this->objectManager->get(\Magento\Catalog\Model\Product::class);

        return $product->setData([
            'news_from_date' => $fromDate,
            'news_to_date' => $toDate
        ]);
    }
}
