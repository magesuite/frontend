<?php

namespace MageSuite\Frontend\Helper;

class Optimize extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_GOOGLE_OPTIMIZE_CONTAINER_ID_PATH = 'google/optimize/optimize_container_id';

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

    public function getOptimizeContainerId()
    {
        return $this->scopeConfig->getValue(
            self::XML_GOOGLE_OPTIMIZE_CONTAINER_ID_PATH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
