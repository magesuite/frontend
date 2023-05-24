<?php

namespace MageSuite\Frontend\Model\Category\Attribute\Source;

use Magento\Catalog\Model\Config\Source\ListMode;

class CategoryDisplay extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    private $supportedModes = [
        'grid-list', 'list-grid'
    ];

    public function getAllOptions()
    {
        $list = new ListMode();
        $modes = $this->supportedModes;
        return array_filter($list->toOptionArray(), function ($option) use ($modes) {
            return in_array($option['value'], $modes);
        });
    }
}
