<?php

namespace MageSuite\Frontend\Plugin;

use Magento\Framework\View\Page\Config;

class UpdateHtmlLangAttribute
{

    public function aroundSetElementAttribute(\Magento\Framework\View\Page\Config $subject, callable $proceed, $elementType, $attribute, $value)
    {
        if ($elementType == Config::ELEMENT_TYPE_HTML && $attribute == Config::HTML_ATTRIBUTE_LANG && strlen($value) != 2) {
            $value = substr($value, 0, 2);
        }
        $proceed($elementType, $attribute, $value);
    }

}
