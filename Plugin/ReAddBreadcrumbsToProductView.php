<?php

namespace MageSuite\Frontend\Plugin;

class ReAddBreadcrumbsToProductView
{
    /**
     * @param \Magento\Catalog\Block\Product\View $subject
     * @param $result
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function afterSetLayout(\Magento\Catalog\Block\Product\View $subject, $result) {
        $subject->getLayout()->createBlock(\Magento\Catalog\Block\Breadcrumbs::class);

        return $result;
    }
}
