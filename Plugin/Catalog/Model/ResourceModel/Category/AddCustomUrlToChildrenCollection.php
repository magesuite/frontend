<?php

namespace MageSuite\Frontend\Plugin\Catalog\Model\ResourceModel\Category;

class AddCustomUrlToChildrenCollection
{
    public function afterGetChildrenCategories($subject, $result)
    {
        $result->addAttributeToSelect(\MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL);

        return $result;
    }
}
