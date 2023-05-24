<?php
/**
 * @author    Dariusz Matoga <dariusz.matoga@creativestyle.pl>
 * @copyright 2017 creativestyle
 */

namespace MageSuite\Frontend\Model\Config\Source;

class SortingDestination implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'asc', 'label' => __('Ascending')],
            ['value' => 'desc', 'label' => __('Descending')],
        ];
    }
}
