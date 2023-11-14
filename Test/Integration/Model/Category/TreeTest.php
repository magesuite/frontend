<?php

namespace MageSuite\Frontend\Test\Integration\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class TreeTest extends \PHPUnit\Framework\TestCase
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
     * @var \MageSuite\Frontend\Model\Category\Tree
     */
    private $categoryTree;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->categoryTree = $this->objectManager
            ->get(\MageSuite\Frontend\Model\Category\Tree::class);

        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    public static function loadCategoriesFixture()
    {
        require __DIR__.'/../../_files/categories.php';
    }

    public static function loadCategoriesFixtureRollback()
    {
        require __DIR__.'/../../_files/categories_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategoriesFixture
     */
    public function testItReturnsCategoryTree()
    {
        $this->itReturnCategoryTree();
        $this->itReturnFilteredCategoryTree();
        $this->itReturnCategoryTreeWithDifferentRoot();

    }

    private function itReturnCategoryTree()
    {
        $categoryId = 333;
        $categoryTree = $this->categoryTree->getCategoryTree();

        $this->assertArrayHasKey('name', $categoryTree[$categoryId]);
        $this->assertCount(3, $categoryTree[$categoryId]['children']);
        $this->assertCount(0, $categoryTree[$categoryId]['parents']);
    }

    private function itReturnFilteredCategoryTree()
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

    private function itReturnCategoryTreeWithDifferentRoot()
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
