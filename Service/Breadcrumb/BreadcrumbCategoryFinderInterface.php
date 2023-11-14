<?php

namespace MageSuite\Frontend\Service\Breadcrumb;

interface BreadcrumbCategoryFinderInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Catalog\Model\Category
     */
    public function getCategory(\Magento\Catalog\Api\Data\ProductInterface $product);
}
