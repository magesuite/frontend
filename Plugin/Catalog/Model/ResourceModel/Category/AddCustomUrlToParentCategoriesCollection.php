<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Catalog\Model\ResourceModel\Category;

class AddCustomUrlToParentCategoriesCollection
{
    public function __construct(
        protected \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
    }

    public function aroundGetParentCategories(
        \Magento\Catalog\Model\ResourceModel\Category $subject,
        callable $proceed,
        \Magento\Catalog\Model\Category $category
    ): array {
        $pathIds = array_reverse(explode(',', (string)$category->getPathInStore()));
        $categories = $this->categoryCollectionFactory->create();

        return $categories->setStore(
            $this->storeManager->getStore()
        )->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'url_key'
        )->addAttributeToSelect(
            \MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL
        )->addFieldToFilter(
            'entity_id',
            ['in' => $pathIds]
        )->addFieldToFilter(
            'is_active',
            1
        )->load()->getItems();
    }
}
