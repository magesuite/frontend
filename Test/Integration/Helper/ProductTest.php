<?php

namespace MageSuite\Frontend\Test\Integration\Helper;

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
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \MageSuite\Frontend\Helper\Product
     */
    private $productHelper;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);

        $this->productHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Product::class);
    }

    public static function loadProductWithReviewsFixture()
    {
        require __DIR__ . '/../_files/product_with_reviews.php';
    }

    public static function loadProductWithReviewsFixtureRollback()
    {
        require __DIR__ . '/../_files/product_with_reviews_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadProductWithReviewsFixture
     */
    public function testItReturnsReviewSummary()
    {
        $productId = 555;
        $product = $this->productRepository->getById($productId);

        $reviewSummary = $this->productHelper->getReviewSummary($product, true);

        $this->assertArrayHasKey('data', $reviewSummary);
        $this->assertCount(4, $reviewSummary['data']);
        $this->assertEquals(5, $reviewSummary['data']['maxStars']);
        $this->assertEquals(1, $reviewSummary['data']['count']);

        $this->assertArrayHasKey('votes', $reviewSummary['data']);
        $this->assertCount(5, $reviewSummary['data']['votes']);
        $this->assertEquals(2, $reviewSummary['data']['votes'][2]);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadProductWithReviewsFixture
     */
    public function testItReturnsEmptyReviewSummary()
    {
        $productId = 556;
        $product = $this->productRepository->getById($productId);

        $reviewSummary = $this->productHelper->getReviewSummary($product);

        $this->assertArrayHasKey('data', $reviewSummary);
        $this->assertCount(4, $reviewSummary['data']);

        $this->assertEquals(0, $reviewSummary['data']['count']);

        $this->assertArrayHasKey('votes', $reviewSummary['data']);
        $this->assertCount(5, $reviewSummary['data']['votes']);
        $this->assertEquals(0, $reviewSummary['data']['votes'][2]);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadProductWithReviewsFixture
     */
    public function testItReturnsCorrectAddToCartUrl()
    {
        $product = $this->productRepository->get('first_product');

        $url = $this->productHelper->getAddToCartUrl($product->getId());

        $this->assertEquals('http://localhost/index.php/checkout/cart/add/product/555/', $url);
    }


    public static function loadConfigurableProduct()
    {
        require __DIR__ . '/../_files/configurable_product.php';
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/ConfigurableProduct/_files/configurable_products.php
     * @magentoDataFixture loadConfigurableProduct
     */
    public function testItReturnsCorrectSalePercentageForConfigurableProduct()
    {
        $product = $this->productRepository->get('configurable');

        $salePercentage = $this->productHelper->getSalePercentage($product);

        $this->assertEquals(68, $salePercentage);
    }


    public static function loadBundleProduct()
    {
        require __DIR__ . '/../_files/bundle_product.php';
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Bundle/_files/product.php
     * @magentoDataFixture loadBundleProduct
     */
    public function testItReturnsCorrectSalePercentageForBundleProduct()
    {
        $product = $this->productRepository->get('bundle-product');

        $salePercentage = $this->productHelper->getSalePercentage($product);

        $this->assertEquals(35, $salePercentage);
    }

}
