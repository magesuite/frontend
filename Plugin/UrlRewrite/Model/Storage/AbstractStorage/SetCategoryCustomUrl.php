<?php

namespace MageSuite\Frontend\Plugin\UrlRewrite\Model\Storage\AbstractStorage;

class SetCategoryCustomUrl
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $categoryResource;

    /**
     * @var \MageSuite\Frontend\Helper\Category
     */
    protected $categoryHelper;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \MageSuite\Frontend\Helper\Category $categoryHelper
    ){
        $this->categoryResource = $categoryResource;
        $this->categoryHelper = $categoryHelper;
    }

    public function afterFindOneByData(\Magento\UrlRewrite\Model\Storage\AbstractStorage $subject, $result, array $data)
    {
        if(empty($result)){
            return $result;
        }

        if($result->getEntityType() != \Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite::ENTITY_TYPE_CATEGORY){
            return $result;
        }

        $customUrl = $this->getCategoryCustomUrl($result->getEntityId(), $result->getStoreId());

        if(empty($customUrl)){
            return $result;
        }

        $preparedCategoryCustomUrl = $this->categoryHelper->prepareCategoryCustomUrl($customUrl);

        $result->setTargetPath($preparedCategoryCustomUrl);
        $result->setRedirectType(\Magento\UrlRewrite\Model\OptionProvider::TEMPORARY);

        if(strpos($preparedCategoryCustomUrl, 'http') !== false){
            $result->setEntityType('custom');
            $result->setEntityId(0);
        }

        return $result;
    }

    protected function getCategoryCustomUrl($categoryId, $storeId)
    {
        return $this->categoryResource->getAttributeRawValue($categoryId, \MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL, $storeId);
    }
}
