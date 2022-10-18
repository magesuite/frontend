<?php

namespace MageSuite\Frontend\Service\Breadcrumb;

class FirstCategoryFinder implements BreadcrumbCategoryFinderInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * Finds first category in product in correct store
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Model\Category
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

        $collection = $this->categoryCollectionFactory->create();
        $collection
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', $categoryIds)
            ->addFieldToFilter('path', ['like' => '%/' . $rootCategoryId . '/%'])
            ->addAttributeToSort('entity_id', 'ASC');

        return $collection->getFirstItem();
    }
}