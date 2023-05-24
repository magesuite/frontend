<?php

namespace MageSuite\Frontend\Block\Product\View;

class Tile extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * Additional elements used to influence cache key
     * @var array
     */
    protected $cacheKeyElements = [];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->setTemplate('product/tile.phtml');
        $this->setData('cache_lifetime', 86400);
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
    }

    public function getIdentities() {
        return $this->getData('product')->getIdentities();
    }

    /**
     * Because of incompatibility with Magento interceptors system for this class we had to
     * remove splat operator in favor of multiple optional arguments
     */
    public function setCacheKeyElements(
        $arg1 = null,
        $arg2 = null,
        $arg3 = null,
        $arg4 = null,
        $arg5 = null,
        $arg6 = null,
        $arg7 = null,
        $arg8 = null,
        $arg9 = null,
        $arg10 = null,
        $arg11 = null,
        $arg12 = null,
        $arg13 = null,
        $arg14 = null,
        $arg15 = null,
        $arg16 = null,
        $arg17 = null,
        $arg18 = null,
        $arg19 = null,
        $arg20 = null
    ) {
        $this->cacheKeyElements = func_get_args();

        return $this;
    }

    public function _toHtml()
    {
        $cacheKey = $this->getCacheKey();

        $this->setData('cache_key', $cacheKey);

        return parent::_toHtml();
    }

    /**
     * Build cache key based on data that might affect tile rendering and additional data passed from template
     * @param array $data
     * @return string
     */
    public function getCacheKey()
    {
        $data = $this->getData();
        $product = $data['product'];
        $store = $this->storeManager->getStore();

        $cacheKeyDefaultElements = [
            $product->getFinalPrice(),
            $store->getId(),
            $store->getCurrentCurrencyCode(),
            $this->customerSession->getCustomerGroupId()
        ];

        $cacheKey = array_merge($cacheKeyDefaultElements, $this->cacheKeyElements);

        return 'product_tile_' . $product->getId().'_'.md5(implode('|', $cacheKey));
    }
}
