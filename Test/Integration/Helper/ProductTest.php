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
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \MageSuite\Frontend\Helper\Product
     */
    private $productHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
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
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadProductWithReviewsFixture
     */
    public function testItReturnsReviewSummary()
    {
        $productId = 555;
        $product = $this->productRepository->getById($productId);

        $this->registry->register('product', $product);

        $reviewSummary = $this->productHelper->getReviewSummary($product, true);

        $this->assertArrayHasKey('data', $reviewSummary);
        $this->assertCount(5, $reviewSummary['data']);
        $this->assertEquals(5, $reviewSummary['data']['maxStars']);
        $this->assertEquals(1, $reviewSummary['data']['count']);

        $this->assertArrayHasKey('votes', $reviewSummary['data']);
        $this->assertCount(5, $reviewSummary['data']['votes']);
        $this->assertEquals(1, $reviewSummary['data']['votes'][2]);

        $this->assertArrayHasKey('ratings', $reviewSummary['data']);

        $this->assertEquals(1, $reviewSummary['data']['ratings'][1]['starsAmount']);
        $this->assertEquals(2, $reviewSummary['data']['ratings'][2]['starsAmount']);
        $this->assertEquals(3, $reviewSummary['data']['ratings'][3]['starsAmount']);

        $this->assertEquals('Quality', $reviewSummary['data']['ratings'][1]['label']);
        $this->assertEquals('Value', $reviewSummary['data']['ratings'][2]['label']);
        $this->assertEquals('Price', $reviewSummary['data']['ratings'][3]['label']);
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
        $this->assertCount(5, $reviewSummary['data']);

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
}
