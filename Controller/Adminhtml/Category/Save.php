<?php

namespace MageSuite\Frontend\Controller\Adminhtml\Category;


class Save extends \Magento\Catalog\Controller\Adminhtml\Category\Save
{

    /**
     * The list of inputs that need to convert from string to boolean
     * @var array
     */
    protected $stringToBoolInputs = [
        'custom_use_parent_settings',
        'custom_apply_to_products',
        'is_active',
        'include_in_menu',
        'is_anchor',
        'use_default' => ['url_key'],
        'use_config' => ['available_sort_by', 'filter_price_range', 'default_sort_by', 'category_view']
    ];

}
