<?php

namespace MageSuite\Frontend\Plugin\Sitemap\Model\ItemProvider\Category;

class ReplaceCategoryUrlWithCustomCategoryUrl
{
    /**
     * @var \MageSuite\Frontend\Model\ResourceModel\Category\Collection
     */
    protected $categoryCollection;

    /**
     * @var \Magento\Sitemap\Model\SitemapItemInterfaceFactory
     */
    protected $itemFactory;

    /**
     * @param \MageSuite\Frontend\Model\ResourceModel\Category\Collection $categoryCollection
     * @param \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
     */
    public function __construct(
        \MageSuite\Frontend\Model\ResourceModel\Category\Collection $categoryCollection,
        \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
    )
    {
        $this->categoryCollection = $categoryCollection;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @param \Magento\Sitemap\Model\ItemProvider\Category $category
     * @param $result
     * @param int $storeId
     * @return array
     * @throws \Zend_Db_Statement_Exception
     */
    public function afterGetItems(\Magento\Sitemap\Model\ItemProvider\Category $category, $result, int $storeId)
    {
        if (empty($result)) {
            return $result;
        }

        $categoriesIds = array_keys($result);
        $categoriesCustomUrlAttributes = $this->categoryCollection->getCategoriesCustomUrlAttributes($categoriesIds, $storeId);

        foreach ($result as $categoryId => $categoryData) {
            if (!isset($categoriesCustomUrlAttributes[$categoryId]['category_custom_url']) || !$categoriesCustomUrlAttributes[$categoryId]['category_custom_url']) {
                continue;
            }
            $result[$categoryId] = $this->itemFactory->create([
                'url' => $categoriesCustomUrlAttributes[$categoryId]['category_custom_url'],
                'updatedAt' => $categoryData->getUpdatedAt(),
                'images' => $categoryData->getImages(),
                'priority' => $categoryData->getPriority(),
                'changeFrequency' => $categoryData->getChangeFrequency()
            ]);
        }

        return $result;
    }
}
