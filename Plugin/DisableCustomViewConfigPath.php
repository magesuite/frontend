<?php

namespace MageSuite\Frontend\Plugin;

class DisableCustomViewConfigPath
{
    public function aroundGetCustomViewConfigPath(\Magento\Framework\View\Design\Theme\Customization\Path $subject, callable $proceed, ...$args) {
        return null;
    }
}
