<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CategoryViewTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;

    public function setUp(): void
    {
        $this->categoryRepository = \Magento\TestFramework\ObjectManager::getInstance()
            ->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories_with_changed_view.php
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('provideDateToChangeCategoryViewOnDifferentStoreTest')]
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
}
