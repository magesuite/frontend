<?php

namespace MageSuite\Frontend\Service\Breadcrumb;

class FirstCategoryFinder implements BreadcrumbCategoryFinderInterface
{
    protected \MageSuite\Frontend\Model\ResourceModel\Category\FirstCategoryFinder $firstCategoryFinder;
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;
    protected \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Frontend\Model\ResourceModel\Category\FirstCategoryFinder $firstCategoryFinder,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    )
    {
        $this->storeManager = $storeManager;
        $this->firstCategoryFinder = $firstCategoryFinder;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Finds first category in product in correct store
     */
    public function getCategory(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        $productCategories = $product->getAvailableInCategories();

        if (empty($productCategories) or !is_array($productCategories)) {
            return null;
        }

        return $this->getFirstCategoryForStore($productCategories, $product->getStoreId());
    }

    private function getFirstCategoryForStore($categoryIds, $storeId)
    {
        $rootCategoryId = $this->storeManager->getStore($storeId)->getRootCategoryId();
        $firstCategoryId = $this->firstCategoryFinder->getFirstCategoryIdForStore($categoryIds, $rootCategoryId);

        if (!is_numeric($firstCategoryId)) {
            return null;
        }

        return $this->categoryRepository->get($firstCategoryId);
    }
}
