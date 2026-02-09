<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Helper\Product;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class StockDataTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\Frontend\Helper\Product\StockData $stockDataHelper;
    protected ?\MageSuite\ContentConstructorFrontend\DataProviders\ProductCarouselDataProvider $dataProvider;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->stockDataHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Product\StockData::class);

        $this->dataProvider = $this->objectManager->get(\MageSuite\ContentConstructorFrontend\DataProviders\ProductCarouselDataProvider::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/products.php
     * @magentoConfigFixture current_store cataloginventory/options/show_out_of_stock 1
     */
    public function testItReturnsCorrectProductsData(): void
    {
        $products = $this->dataProvider->getProducts(['category_id' => 333]);

        $expected = [
            'in_stock_with_qty' => ['stock' => true, 'qty' => 100],
            'in_stock_without_qty' => ['stock' => true, 'qty' => 0],
            'out_of_stock_with_qty' => ['stock' => false, 'qty' => 100]
        ];

        $result = [];
        foreach($products as $product){
            $result[$product['sku']] = ['stock' => $product['stock'], 'qty' => $product['qty']];
        }

        $this->assertEquals($expected, $result);
    }
}
