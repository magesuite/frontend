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

        if($currentAction != self::CATALOG_PRODUCT_VIEW) {
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

        $layout->getUpdate()->addHandle(self::LAYOUT_HANDLE_NAME);
    }
}
