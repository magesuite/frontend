<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Service\Breadcrumb;

class FirstCategoryFinder implements BreadcrumbCategoryFinderInterface
{
    protected array $categoryCache = [];

    public function __construct(
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \MageSuite\Frontend\Model\ResourceModel\Category\FirstCategoryFinder $firstCategoryFinder,
        protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getCategory(\Magento\Catalog\Api\Data\ProductInterface $product): ?\Magento\Catalog\Api\Data\CategoryInterface
    {
        $productCategories = $product->getAvailableInCategories();

        if (empty($productCategories) || !is_array($productCategories)) {
            return null;
        }

        $cacheKey = (int)$product->getId() . '_' . (int)$product->getStoreId();

        if (!array_key_exists($cacheKey, $this->categoryCache)) {
            $this->categoryCache[$cacheKey] = $this->getFirstCategoryForStore($productCategories, (int)$product->getStoreId());
        }

        return $this->categoryCache[$cacheKey];
    }

    protected function getFirstCategoryForStore(array $categoryIds, int $storeId): ?\Magento\Catalog\Api\Data\CategoryInterface
    {
        $rootCategoryId = $this->storeManager->getStore($storeId)->getRootCategoryId();
        $firstCategoryId = $this->firstCategoryFinder->getFirstCategoryIdForStore($categoryIds, $rootCategoryId);

        if (!is_numeric($firstCategoryId)) {
            return null;
        }

        return $this->categoryRepository->get($firstCategoryId);
    }
}
