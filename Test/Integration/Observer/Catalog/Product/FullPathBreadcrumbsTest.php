<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Observer\Catalog\Product;

class FullPathBreadcrumbsTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository;
    protected ?\MageSuite\Frontend\Service\Breadcrumb\BreadcrumbCategoryFinderInterface $categoryFinder;
    protected ?\Magento\Framework\EntityManager\EventManager $eventManager;
    protected ?\Magento\Framework\Registry $registry;

    public function setUp(): void
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
    public function testCurrentCategoryIsFilledWithFirstFoundCategory(string $sku, int $expectedCategoryId): void
    {
        if (get_class($this->categoryFinder) != \MageSuite\Frontend\Service\Breadcrumb\FirstCategoryFinder::class) {
            $this->markTestSkipped();
        }

        $product = $this->productRepository->get($sku);
        $this->assertNull($this->registry->registry('current_category'));

        $request = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $request->expects($this->any())
            ->method('getFullActionName')
            ->willReturn('catalog_product_view');

        $controller = $this->createMock(\Magento\Framework\App\Action\Action::class);
        $controller->expects($this->any())
            ->method('getRequest')
            ->willReturn($request);

        $this->eventManager->dispatch(
            'catalog_controller_product_init_after', [
                'product' => $product,
                'controller_action' => $controller
            ]
        );

        $currentCategory = $this->registry->registry('current_category');

        $this->assertInstanceOf(\Magento\Catalog\Model\Category::class, $currentCategory);
        $this->assertEquals($expectedCategoryId, $currentCategory->getId());
    }

    public static function getProductsSkusAndExpectedCategoryIds(): array
    {
        return [
            ['simple', 3],
            ['12345', 4],
        ];
    }
}
