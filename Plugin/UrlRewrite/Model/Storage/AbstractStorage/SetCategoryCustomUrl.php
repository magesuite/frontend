<?php

namespace MageSuite\Frontend\Plugin\UrlRewrite\Model\Storage\AbstractStorage;

class SetCategoryCustomUrl
{
    protected \Magento\Catalog\Model\ResourceModel\Category $categoryResource;
    protected \MageSuite\Frontend\Helper\Category $categoryHelper;
    protected array $urlsCache = [];

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \MageSuite\Frontend\Helper\Category $categoryHelper
    ) {
        $this->categoryResource = $categoryResource;
        $this->categoryHelper = $categoryHelper;
    }

    public function afterFindOneByData(\Magento\UrlRewrite\Model\Storage\AbstractStorage $subject, $result, array $data)
    {
        if (empty($result)) {
            return $result;
        }

        if ($result->getEntityType() != \Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite::ENTITY_TYPE_CATEGORY) {
            return $result;
        }

        $customUrl = $this->getCategoryCustomUrl($result->getEntityId(), $result->getStoreId());

        if (empty($customUrl)) {
            return $result;
        }

        $preparedCategoryCustomUrl = $this->categoryHelper->prepareCategoryCustomUrl($customUrl);

        $result->setTargetPath($preparedCategoryCustomUrl);
        $result->setRedirectType($this->categoryHelper->getCustomUrlRedirectionType());

        if (strpos($preparedCategoryCustomUrl, 'http') !== false) {
            $result->setEntityType('custom');
            $result->setEntityId(0);
        }

        return $result;
    }

    protected function getCategoryCustomUrl($categoryId, $storeId)
    {
        $key = sprintf('%s_%s', $categoryId, $storeId);

        if (!array_key_exists($key, $this->urlsCache)) {
            $this->urlsCache[$key] = $this->categoryResource->getAttributeRawValue(
                $categoryId,
                \MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL,
                $storeId
            );
        }

        return $this->urlsCache[$key];
    }
}
