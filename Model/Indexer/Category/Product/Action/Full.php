<?php

namespace MageSuite\Frontend\Model\Indexer\Category\Product\Action;

class Full extends \Magento\Catalog\Model\Indexer\Category\Product\Action\Full
{
    /**
     * Value is overriten because of this bug: https://github.com/magento/magento2/issues/7968
     * Should be removed after bug fix
     * @return bool
     */
    protected function isRangingNeeded()
    {
        return false;
    }
}
