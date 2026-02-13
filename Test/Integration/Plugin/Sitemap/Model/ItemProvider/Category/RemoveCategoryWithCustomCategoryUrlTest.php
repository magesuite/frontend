<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Plugin\Sitemap\Model\ItemProvider\Category;

class RemoveCategoryWithCustomCategoryUrlTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\Sitemap\Model\ItemProvider\Category $categorySitemapItemProvider;
    protected ?\Magento\Store\Model\StoreManagerInterface $storeManager;

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
    public function testItRemoveCategoryUrlWithCustomCategoryUrl(): void
    {
        $storeId = (int)$this->storeManager->getStore()->getId();
        $items = $this->categorySitemapItemProvider->getItems($storeId);
        $categoryIds = [338, 339, 340];

        foreach($categoryIds as $categoryId){
            $this->assertArrayNotHasKey($categoryId, $items);
        }
    }
}
