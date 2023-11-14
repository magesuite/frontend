<?php

namespace MageSuite\Frontend\Block\Product\ProductList;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    public function getLimit()
    {
        return 1000;
    }

    public function isExpanded()
    {
        return true;
    }
}
