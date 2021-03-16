<?php

namespace MageSuite\Frontend\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CategoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \MageSuite\Frontend\Helper\Category
     */
    protected $categoryHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->categoryHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Category::class);

        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    public static function loadCategoriesFixture()
    {
        require __DIR__ . '/../_files/categories.php';
    }

    public static function loadCategoriesFixtureRollback()
    {
        require __DIR__ . '/../_files/categories_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategoriesFixture
     */
    public function testItReturnsCategoryNode()
    {
        $categoryId = 335;
        $categoryNode = $this->getCategoryNode($categoryId);

        $this->assertArrayHasKey('name', $categoryNode);
        $this->assertArrayHasKey('children', $categoryNode);
        $this->assertArrayHasKey('parents', $categoryNode);

        $this->assertEquals('Second subcategory', $categoryNode['children'][$categoryId]['name']);
        $this->assertEquals(true, $categoryNode['children'][$categoryId]['current']);
        $this->assertEquals(false, $categoryNode['children'][334]['current']);

        $this->assertEquals('Main category', $categoryNode['children'][$categoryId]['parents'][333]['name']);
    }

    public function getCategoryNode($categoryId, $returnCurrent = false)
    {
        $category = $this->categoryRepository->get($categoryId);
        $categoryTree = $this->categoryHelper->getCategoryNode($category, $returnCurrent);

        return $categoryTree;
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategoriesFixture
     */
    public function testItReturnsCurrentCategory()
    {
        $categoryId = 335;
        $categoryNode = $this->getCategoryNode($categoryId, true);

        $this->assertEquals('Second subcategory', $categoryNode['name']);
        $this->assertEquals(335, $categoryNode['entity_id']);
        $this->assertEquals('http://localhost/index.php/main-category/second-subcategory.html', $categoryNode['url']);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadCategoriesFixture
     */
    public function testItReturnsImageTeaserAttributes()
    {
        $categoryId = 335;
        $category = $this->categoryRepository->get($categoryId);

        $url = $this->categoryHelper->getImageTeaser($category);
        $url = str_replace('pub/', '', $url);
        $this->assertEquals('teaser.png', $category->getImageTeaser());
        $this->assertEquals(
            'http://localhost/media/catalog/category/teaser.png',
            $url
        );

        $this->assertEquals('Image Teaser Slogan', $category->getImageTeaserSlogan());
        $this->assertEquals('Image Teaser Description', $category->getImageTeaserDescription());
        $this->assertEquals('Image Teaser CTA Label', $category->getImageTeaserCtaLabel());
        $this->assertEquals('url', $category->getImageTeaserCtaLink());
        $this->assertEquals('http://localhost/index.php/url', $this->categoryHelper->prepareCategoryCustomUrl($category->getImageTeaserCtaLink()));
    }
}
