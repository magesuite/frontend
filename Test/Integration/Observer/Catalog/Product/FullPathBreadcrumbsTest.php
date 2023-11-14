<?php

namespace MageSuite\Frontend\Test\Integration\Observer\Catalog\Product;

class FullPathBreadcrumbsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \MageSuite\Frontend\Service\Breadcrumb\BreadcrumbCategoryFinderInterface
     */
    protected $categoryFinder;

    /**
     * @var \Magento\Framework\EntityManager\EventManager
     */
    protected $eventManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->categoryFinder = $this->objectManager->get(\MageSuite\Frontend\Service\Breadcrumb\BreadcrumbCategoryFinderInterface::class);
        $this->eventManager = $this->objectManager->get(\Magento\Framework\EntityManager\EventManager::class);
        $this->registry = $this->objectManager->get(\Magento\Framework\Registry::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoDbIsolation enabled
     * @magentoAppArea frontend
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     * @dataProvider getProductsSkusAndExpectedCategoryIds
     */
    public function testCurrentCategoryIsFilledWithFirstFoundCategory($sku, $expectedCategoryId) {
        if(get_class($this->categoryFinder) != \MageSuite\Frontend\Service\Breadcrumb\FirstCategoryFinder::class) {
            $this->markTestSkipped();
        }

        $product = $this->productRepository->get($sku);

        $this->assertNull($this->registry->registry('current_category'));

        $this->eventManager->dispatch('catalog_controller_product_init_after', ['product' => $product]);

        $currentCategory = $this->registry->registry('current_category');

        $this->assertInstanceOf(\Magento\Catalog\Model\Category::class, $currentCategory);
        $this->assertEquals($expectedCategoryId, $currentCategory->getId());
    }

    public static function getProductsSkusAndExpectedCategoryIds() {
        return [
            ['simple', 3],
            ['12345', 4],
        ];
    }
}
