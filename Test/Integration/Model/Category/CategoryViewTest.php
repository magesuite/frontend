<?php

namespace MageSuite\Frontend\Test\Integration\Model\Category;
use Magento\Catalog\Model\Category;
use Magento\Store\Model\Store;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CategoryViewTest extends \PHPUnit\Framework\TestCase
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
     * @magentoDataFixture loadCategoriesWithChangedView
     * @dataProvider provideDateToChangeCategoryViewOnDifferentStoreTest
     * @param integer $categoryId
     * @param string|null $storeCode
     * @param string|null $expected
     */
    public function testChangeCategoryViewOnDifferentStore($categoryId, $storeCode, $expected)
    {
        /** @var Category $category */
        $category = $this->categoryRepository->get($categoryId, $storeCode);

        $this->assertEquals(
            $expected,
            $category->getCustomAttribute('category_view') === null
                ? null
                : $category->getCustomAttribute('category_view')->getValue()
        );
    }

    /**
     * @return array
     */
    public function provideDateToChangeCategoryViewOnDifferentStoreTest()
    {
        return [
            [435, null, null],
            [436, null, 'grid-list'],
            [437, null, 'list'],
            [437, 'default', 'list'],
            [437, 'admin', 'grid']
        ];
    }

    public static function loadCategoriesWithChangedView()
    {
        require __DIR__.'/../../_files/categories_with_changed_view.php';
    }

    public static function loadCategoriesWithChangedViewRollback()
    {
        require __DIR__.'/../../_files/categories_with_changed_view_rollback.php';
    }
}
