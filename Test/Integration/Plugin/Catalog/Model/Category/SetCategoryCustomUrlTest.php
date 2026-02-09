<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Plugin\Catalog\Model\Category;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class SetCategoryCustomUrlTest extends \PHPUnit\Framework\TestCase
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
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories.php
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/products.php
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/pages.php
     */
    public function testCategoryCustomUrl(): void
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
}
