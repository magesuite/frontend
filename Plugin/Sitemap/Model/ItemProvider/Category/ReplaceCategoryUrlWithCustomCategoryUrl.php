<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Sitemap\Model\ItemProvider\Category;

class ReplaceCategoryUrlWithCustomCategoryUrl
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

            if (str_starts_with($customUrl, 'http')) {
                unset($result[$categoryId]);
                continue;
            }

            $result[$categoryId] = $this->itemFactory->create([
                'url' => ltrim($customUrl, '/'),
                'updatedAt' => $result[$categoryId]->getUpdatedAt(),
                'images' => $result[$categoryId]->getImages(),
                'priority' => $result[$categoryId]->getPriority(),
                'changeFrequency' => $result[$categoryId]->getChangeFrequency()
            ]);
        }

        return $result;
    }
}
