<?php

namespace MageSuite\Frontend\Test\Integration\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class FeaturedProductsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var \MageSuite\Frontend\Helper\Category
     */
    private $categoryHelper;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
        $this->categoryHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Category::class);
    }

    public static function loadCategoriesWithProductsFixture()
    {
        require __DIR__.'/../../_files/categories_with_products.php';

        $indexerRegistry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Framework\Indexer\IndexerRegistry::class);
        $indexerRegistry->get(\Magento\CatalogSearch\Model\Indexer\Fulltext::INDEXER_ID)->reindexAll();
    }

    public static function loadCategoriesWithProductsFixtureRollback()
    {
        require __DIR__.'/../../_files/categories_with_products_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategoriesWithProductsFixture
     */
    public function testItReturnsCorrectCategoryData()
    {
        $categoryId = 334;

        $category = $this->categoryRepository->get($categoryId);

        $this->itReturnsCategoryData($category);
        $this->itReturnsFeaturedProducts($category);
    }

    private function itReturnsCategoryData($category)
    {
        $this->assertEquals('{"555":"","556":"","557":"","558":""}', $category->getFeaturedProducts());
        $this->assertEquals('Featured Products Header', $category->getFeaturedProductsHeader());
    }

    public function itReturnsFeaturedProducts($category)
    {
        $featuredProducts = $this->categoryHelper->getFeaturedProducts($category);

        $this->assertCount(2, $featuredProducts);

        $this->assertArrayHasKey('name', $featuredProducts[0]);
        $this->assertArrayHasKey('price', $featuredProducts[0]);
        $this->assertArrayHasKey('stock', $featuredProducts[0]);
        $this->assertArrayHasKey('swatches', $featuredProducts[0]);

        $this->assertEquals('Second product', $featuredProducts[0]['name']);
        $this->assertEquals('First product', $featuredProducts[1]['name']);

    }
}
