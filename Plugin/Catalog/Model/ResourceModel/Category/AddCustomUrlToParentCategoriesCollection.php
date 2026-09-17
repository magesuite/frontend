<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Catalog\Model\ResourceModel\Category;

class AddCustomUrlToParentCategoriesCollection
{
    public function __construct(
        protected \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
    }

    public function afterGetParentCategories(\Magento\Catalog\Model\ResourceModel\Category $subject, array $result): array
    {
        if (empty($result)) {
            return $result;
        }

        $categories = $this->fetchCategoriesWithCustomUrl((int)$subject->getStoreId(), array_keys($result));

        foreach ($result as $item) {
            $category = $categories->getItemById($item->getId());
            $customUrl = $category?->getData(\MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL);

            $item->setData(\MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL, $customUrl);
        }

        return $result;
    }

    protected function fetchCategoriesWithCustomUrl(
        int $storeId,
        array $categoryIds
    ): \Magento\Catalog\Model\ResourceModel\Category\Collection {
        $categories = $this->categoryCollectionFactory->create()
            ->setStoreId($storeId)
            ->addAttributeToSelect(\MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL)
            ->addFieldToFilter('entity_id', ['in' => $categoryIds]);

        return $categories;
    }
}
