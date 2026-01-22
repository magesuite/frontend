<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CategoryViewTest extends \PHPUnit\Framework\TestCase
{
    private ?\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;

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
     */
    public function testChangeCategoryViewOnDifferentStore(int $categoryId, ?string $storeCode, ?string $expected): void
    {
        /** @var \Magento\Catalog\Model\Category $category */
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
    public static function provideDateToChangeCategoryViewOnDifferentStoreTest(): array
    {
        return [
            [435, null, null],
            [436, null, 'grid-list'],
            [437, null, 'list'],
            [437, 'default', 'list'],
            [437, 'admin', 'grid']
        ];
    }

    public static function loadCategoriesWithChangedView(): void
    {
        require __DIR__ . '/../../_files/categories_with_changed_view.php';
    }

    public static function loadCategoriesWithChangedViewRollback(): void
    {
        require __DIR__ . '/../../_files/categories_with_changed_view_rollback.php';
    }
}
