<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class FeaturedProductsTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\TestFramework\ObjectManager $objectManager;
    protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;
    protected \MageSuite\Frontend\Helper\Category $categoryHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->categoryRepository = $this->objectManager->create(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
        $this->categoryHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Category::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories_with_products.php
     */
    public function testItReturnsCorrectCategoryData(): void
    {
        $categoryId = 334;

        $category = $this->categoryRepository->get($categoryId);

        $this->itReturnsCategoryData($category);
        $this->itReturnsFeaturedProducts($category);
    }

    protected function itReturnsCategoryData(\Magento\Catalog\Api\Data\CategoryInterface $category): void
    {
        $this->assertEquals('{"555":"","556":"","557":"","558":""}', $category->getFeaturedProducts());
        $this->assertEquals('Featured Products Header', $category->getFeaturedProductsHeader());
    }

    protected function itReturnsFeaturedProducts(\Magento\Catalog\Api\Data\CategoryInterface $category): void
    {
        $featuredProducts = $this->categoryHelper->getFeaturedProducts($category);

        $this->assertCount(2, $featuredProducts);

        $this->assertArrayHasKey('name', $featuredProducts[0]);
        $this->assertArrayHasKey('price', $featuredProducts[0]);
        $this->assertArrayHasKey('stock', $featuredProducts[0]);
        $this->assertArrayHasKey('swatches', $featuredProducts[0]);

        $this->assertEquals('Second product', $featuredProducts[1]['name']);
        $this->assertEquals('First product', $featuredProducts[0]['name']);

    }
}
