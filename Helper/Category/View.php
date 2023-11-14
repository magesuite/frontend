<?php

namespace MageSuite\Frontend\Helper\Category;

class View extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string|null
     */
    public function getSortDirection()
    {
        return $this->scopeConfig->getValue('catalog/frontend/sort_direction');
    }

}
