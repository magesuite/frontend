<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class TreeTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\TestFramework\ObjectManager $objectManager;

    protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;

    protected \MageSuite\Frontend\Model\Category\Tree $categoryTree;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->categoryTree = $this->objectManager
            ->get(\MageSuite\Frontend\Model\Category\Tree::class);

        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories.php
     */
    public function testItReturnsCategoryTree(): void
    {
        $this->itReturnCategoryTree();
        $this->itReturnFilteredCategoryTree();
        $this->itReturnCategoryTreeWithDifferentRoot();
    }

    protected function itReturnCategoryTree(): void
    {
        $categoryId = 333;
        $categoryTree = $this->categoryTree->getCategoryTree();

        $this->assertArrayHasKey('name', $categoryTree[$categoryId]);
        $this->assertCount(3, $categoryTree[$categoryId]['children']);
        $this->assertCount(0, $categoryTree[$categoryId]['parents']);
    }

    protected function itReturnFilteredCategoryTree(): void
    {
        $categoryId = 333;

        $configuration = [
            'only_included_in_menu' => 1
        ];

        $categoryTree = $this->categoryTree->getCategoryTree($configuration);

        $this->assertArrayHasKey('name', $categoryTree[$categoryId]);
        $this->assertCount(1, $categoryTree[$categoryId]['children']);
        $this->assertCount(0, $categoryTree[$categoryId]['parents']);
    }

    protected function itReturnCategoryTreeWithDifferentRoot(): void
    {
        $categoryId = 334;

        $configuration = [
            'root_category_id' => 333
        ];

        $categoryTree = $this->categoryTree->getCategoryTree($configuration);

        $this->assertArrayHasKey('name', $categoryTree[$categoryId]);
        $this->assertEquals('First subcategory', $categoryTree[$categoryId]['name']);
        $this->assertCount(0, $categoryTree[$categoryId]['children']);
        $this->assertCount(0, $categoryTree[$categoryId]['parents']);
    }
}
