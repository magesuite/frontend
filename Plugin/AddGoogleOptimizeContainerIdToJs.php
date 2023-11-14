<?php

namespace MageSuite\Frontend\Plugin;

class AddGoogleOptimizeContainerIdToJs
{
    /**
     * @var \MageSuite\Frontend\Helper\Optimize
     */
    protected $optimizeHelper;

    public function __construct(
        \MageSuite\Frontend\Helper\Optimize $optimizeHelper
    )
    {
        $this->optimizeHelper = $optimizeHelper;
    }

    public function afterGetPageTrackingData(\Magento\GoogleAnalytics\Block\Ga $subject, $result) {
        $result['optimizeContainerId'] = $this->optimizeHelper->getOptimizeContainerId();

        return $result;
    }
}
