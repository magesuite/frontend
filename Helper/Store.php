<?php

namespace MageSuite\Frontend\Helper;

class Store extends \Magento\Framework\App\Helper\AbstractHelper
{
    private static $currentStore = null;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    )
    {
        parent::__construct($context);

        $this->storeManagerInterface = $storeManagerInterface;
    }

    public function getCurrentStoreId() {
        return $this->getCurrentStore()->getId();
    }

    protected function getCurrentStore() {
        if(self::$currentStore == null) {
            self::$currentStore = $this->storeManagerInterface->getStore();
        }

        return self::$currentStore;
    }
}
