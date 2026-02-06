<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Plugin\Sitemap\Model\ItemProvider\Category;

class ReplaceCategoryUrlWithCustomCategoryUrlTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\Sitemap\Model\ItemProvider\Category $categorySitemapItemProvider;
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->categorySitemapItemProvider = $objectManager->get(\Magento\Sitemap\Model\ItemProvider\Category::class);
        $this->storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/categories.php
     */
    public function testItReplacesCategoryUrlWithCustomCategoryUrl(): void
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $items = $this->categorySitemapItemProvider->getItems($storeId);
        $this->assertEquals('main-category/third-subcategory/subcategory-of-third-subcategory.html', $items[337]->getUrl());
        $this->assertEquals('contact', $items[338]->getUrl());
    }
}
