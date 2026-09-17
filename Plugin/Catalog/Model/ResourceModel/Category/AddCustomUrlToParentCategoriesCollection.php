<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Catalog\Model\ResourceModel\Category;

class AddCustomUrlToParentCategoriesCollection
{
    public function afterGetParentCategories(\Magento\Catalog\Model\ResourceModel\Category $subject, array $result): array
    {
        $storeId = $subject->getStoreId();

        foreach ($result as $category) {
            $customUrl = $subject->getAttributeRawValue(
                $category->getId(),
                \MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL,
                $storeId
            );

            $category->setData(\MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL, $customUrl);
        }

        return $result;
    }
}
