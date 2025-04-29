<?php

namespace MageSuite\Frontend\Helper\Product;

class StockData extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \MageSuite\Frontend\Helper\Product\Stock
     */
    private $stockHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MageSuite\Frontend\Helper\Product\Stock $stockHelper
    )
    {
        parent::__construct($context);
        $this->stockHelper = $stockHelper;
    }

    public function getStockData($productCollection)
    {
        $return = [];
        $productIds = [];

        if (empty($productCollection)) {
            return $return;
        }

        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($productCollection as $productId => $product) {
            $return[$productId] = [
                'salable' => false,
                'qty' => 0
            ];

            if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                $productIds = array_merge($productIds, $product->getTypeInstance()->getUsedProductIds($product));
            } else {
                $productIds[] = $productId;
                $return[$productId]['salable'] = $product->isSalable();
            }
        }

        if (empty($productIds)) {
            return $return;
        }

        $stockStatuses = $this->stockHelper->getStockStatuses($productIds);

        foreach ($productCollection as $productId => $product) {

            if ($product->getTypeId() == \Magento\ConfigurableProduct\Model\Product\Type\Configurable::TYPE_CODE) {
                foreach ($product->getTypeInstance()->getUsedProducts($product) as $simpleProduct) {
                    $stockStatus = $stockStatuses[$simpleProduct->getId()];

                    if ((int)$stockStatus->getStockStatus() === \Magento\CatalogInventory\Model\Stock\Status::STATUS_IN_STOCK and $simpleProduct->isSalable()) {
                        $return[$productId]['salable'] = true;
                        break;
                    }
                }
            } else {
                $return[$productId]['qty'] = (float)$stockStatuses[$productId]->getQty();
            }
        }

        return $return;
    }

}
