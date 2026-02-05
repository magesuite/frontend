<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class CategoryTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\TestFramework\ObjectManager $objectManager;
    protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;
    protected \MageSuite\Frontend\Helper\Category $categoryHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->categoryHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Category::class);

        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories.php
     */
    public function testItReturnsCategoryNode(): void
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

    protected function getCategoryNode(int $categoryId, bool $returnCurrent = false): array
    {
        $category = $this->categoryRepository->get($categoryId);

        return $this->categoryHelper->getCategoryNode($category, $returnCurrent);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories.php
     */
    public function testItReturnsCurrentCategory(): void
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
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories.php
     */
    public function testItReturnsImageTeaserAttributes(): void
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
