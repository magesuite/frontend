<?php

namespace MageSuite\Frontend\Plugin\Bundle\Pricing\Render\FinalPriceBox;

class HideRangePriceInSimplifiedBundle
{
    public function aroundShowRangePrice(
        \Magento\Bundle\Pricing\Render\FinalPriceBox $subject,
        \Closure $proceed
    ) {
        if($subject->getSaleableItem()->getIsSimplifiedBundle()){
            return false;
        }

        return $proceed();
    }
}
