<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Sitemap\Model\ItemProvider\Category;

class RemoveCategoryWithCustomCategoryUrl
{
    public function __construct(
        protected \MageSuite\Frontend\Model\ResourceModel\Category\Collection $categoryCollection,
        protected \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
    ) {}

    public function afterGetItems(\Magento\Sitemap\Model\ItemProvider\Category $category, $result, int $storeId): array
    {
        if (empty($result)) {
            return $result;
        }

        $categoriesCustomUrlAttributes = $this->categoryCollection->getCategoriesCustomUrlAttributes($storeId);

        foreach ($categoriesCustomUrlAttributes as $categoryId => $customUrl) {
            if (empty($result[$categoryId])) {
                continue;
            }

            unset($result[$categoryId]);
        }

        return $result;
    }
}
