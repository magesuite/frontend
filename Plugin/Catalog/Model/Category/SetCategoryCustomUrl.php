<?php

namespace MageSuite\Frontend\Plugin\Catalog\Model\Category;

class SetCategoryCustomUrl
{
    /**
     * @var \MageSuite\Frontend\Helper\Category
     */
    protected $categoryHelper;

    public function __construct(\MageSuite\Frontend\Helper\Category $categoryHelper)
    {
        $this->categoryHelper = $categoryHelper;
    }

    public function afterGetUrl(\Magento\Catalog\Model\Category $subject, $result)
    {
        $customUrl = $subject->getCategoryCustomUrl();

        if(empty($customUrl)){
            return $result;
        }

        return $this->categoryHelper->prepareCategoryCustomUrl($customUrl);
    }
}
