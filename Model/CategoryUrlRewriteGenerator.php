<?php

namespace MageSuite\Frontend\Model;

use Magento\Catalog\Model\Category;

class CategoryUrlRewriteGenerator extends \Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator
{

    protected function updateCategoryUrlForStore($storeId, Category $category = null)
    {
        /**
         * Rewrited Magento core class because wrong url rewrite is created for the new category when we have multistore.
         * Should be removed when this will be fixed in Magento.
         */
        if (!empty($category) AND $category->isObjectNew()) {
            $category->setStoreId($storeId);
            $category->addData(
                [
                    'url_key' => $category->getUrlKey(),
                    'url_path' => $category->getUrlPath()
                ]
            );
        } else {
            parent::updateCategoryUrlForStore($storeId, $category);
        }
    }

}
