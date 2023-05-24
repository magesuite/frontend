<?php

namespace MageSuite\Frontend\Block\Product\ProductList;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    const LIMIT = 100;

    public function getLimit()
    {
        return self::LIMIT;
    }

    public function isExpanded()
    {
        return true;
    }
}
