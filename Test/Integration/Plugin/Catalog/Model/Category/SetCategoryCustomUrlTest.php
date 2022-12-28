<?php

namespace MageSuite\Frontend\Test\Integration\Plugin\Catalog\Model\Category;
use Magento\Catalog\Model\Category;
use Magento\Store\Model\Store;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class SetCategoryCustomUrlTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function setUp(): void
    {
        $this->categoryRepository = \Magento\TestFramework\ObjectManager::getInstance()
            ->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategories
     * @magentoDataFixture loadProducts
     * @magentoDataFixture loadPages
     */
    public function testCategoryCustomUrl()
    {
        $expectedResults = [
            '338' => 'http://localhost/index.php/contact/',
            '339' => 'http://localhost/index.php/site1-default',
            '340' => 'http://localhost/index.php/in-stock-product-with-qty.html',
            '341' => 'http://localhost/index.php/contact/',
            '342' => 'http://localhost/index.php/category-with-custom-url/category-with-broken-directive.html'
        ];

        foreach ($expectedResults as $categoryId => $expectedResult) {
            $category = $this->categoryRepository->get($categoryId);

            $this->assertEquals($expectedResult, $category->getUrl());
        }
    }

    public static function loadCategories()
    {
        require __DIR__.'/../../../../_files/categories.php';

        $indexerRegistry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create(\Magento\Framework\Indexer\IndexerRegistry::class);
        $indexerRegistry->get(\Magento\CatalogSearch\Model\Indexer\Fulltext::INDEXER_ID)->reindexAll();
    }

    public static function loadCategoriesRollback()
    {
        require __DIR__.'/../../../../_files/categories_rollback.php';
    }

    public static function loadPages()
    {
        require __DIR__.'/../../../../_files/pages.php';
    }

    public static function loadPagesRollback()
    {
        require __DIR__ . '/../../../../_files/pages_rollback.php';
    }

    public static function loadProducts()
    {
        require __DIR__ . '/../../../../_files/products.php';
    }

    public static function loadProductsRollback()
    {
        require __DIR__ . '/../../../../_files/products_rollback.php';
    }
}
