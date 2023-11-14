<?php

namespace MageSuite\Frontend\Plugin\ProductTile\Helper\AddToCart;

class MarkSimplifiedBundleAsNotConfigurable
{
    public function aroundCanBeConfigured(\MageSuite\ProductTile\Helper\AddToCart $subject, callable $proceed, \Magento\Catalog\Model\Product $product) {
        if($product->getTypeId() == \Magento\Bundle\Model\Product\Type::TYPE_CODE and $product->getIsSimplifiedBundle()) {
            return false;
        }

        return $proceed($product);
    }
}
