<?php

namespace MageSuite\Frontend\Helper;

class Order extends \Magento\Framework\App\Helper\AbstractHelper
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

    public function canPrintOrder()
    {
        return (bool)$this->scopeConfig->getValue('sales/general/show_print_button', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

}
