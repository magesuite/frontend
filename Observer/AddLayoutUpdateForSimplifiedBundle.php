<?php

namespace MageSuite\Frontend\Observer;

class AddLayoutUpdateForSimplifiedBundle implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    const LAYOUT_HANDLE_NAME = 'catalog_product_view_type_simplified_bundle';
    const CATALOG_PRODUCT_VIEW = 'catalog_product_view';

    const LAYOUT_HANDLE_NAME_CONFIGURE = 'checkout_cart_configure_type_simplified_bundle';
    const CHECKOUT_CART_CONFIGURE = 'checkout_cart_configure';

    const LAYOUT_HANDLE_NAME_CONFIGURE_WISHLIST = 'wishlist_index_configure_type_simplified_bundle';
    const WISHLIST_INDEX_CONFIGURE = 'wishlist_index_configure';

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Registry $registry
    )
    {
        $this->request = $request;
        $this->registry = $registry;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $currentAction = $this->request->getFullActionName();

        if(
            $currentAction != self::CATALOG_PRODUCT_VIEW 
            && $currentAction != self::CHECKOUT_CART_CONFIGURE 
            && $currentAction !=  self::WISHLIST_INDEX_CONFIGURE
        ) {
            return;
        }

        $currentProduct = $this->registry->registry('current_product');

        if(!$currentProduct) {
            return;
        }

        $productType = $currentProduct->getTypeId();

        if($productType != \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE) {
            return;
        }

        $isSimplifiedBundle = $currentProduct->getIsSimplifiedBundle();

        if(!$isSimplifiedBundle) {
            return;
        }

        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getEvent()->getLayout();

        if($currentAction == self::CATALOG_PRODUCT_VIEW) {
            $layout->getUpdate()->addHandle(self::LAYOUT_HANDLE_NAME);
        } else if($currentAction == self::CHECKOUT_CART_CONFIGURE) {
            $layout->getUpdate()->addHandle(self::LAYOUT_HANDLE_NAME_CONFIGURE);
        } else if($currentAction == self::WISHLIST_INDEX_CONFIGURE) {
            $layout->getUpdate()->addHandle(self::LAYOUT_HANDLE_NAME_CONFIGURE_WISHLIST);
        }
    }
}