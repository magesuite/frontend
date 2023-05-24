<?php

namespace MageSuite\Frontend\Model\Product\Type;

class Configurable extends \Magento\ConfigurableProduct\Model\Product\Type\Configurable
{
    protected static $salableUsedProductsCache = [];

    /**
     * Cache for getConfigurableAttributes is broken: https://github.com/magento/magento2/issues/6999
     * @param mixed $configurableAttributes
     * @return bool
     */
    protected function hasCacheData($configurableAttributes)
    {
        $configurableAttributes = $configurableAttributes ? unserialize($configurableAttributes) : $configurableAttributes;

        if (
            (is_array($configurableAttributes) or $configurableAttributes instanceof \Traversable)
            and count($configurableAttributes)
        ) {
            foreach ($configurableAttributes as $attribute) {
                /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute $attribute */
                if ($attribute->getData('options')) {
                    return $configurableAttributes;
                }
            }
        }
        return false;
    }

    /**
     * Retrieve array of salable "subproducts"
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array|null $requiredAttributeIds
     * @return \Magento\Catalog\Model\Product[]
     */
    public function getSalableUsedProducts(\Magento\Catalog\Model\Product $product, $requiredAttributeIds = null)
    {
        if(!isset(self::$salableUsedProductsCache[$product->getId()])) {
            self::$salableUsedProductsCache[$product->getId()] = $this->optimizedGetSalableUsedProducts($product, $requiredAttributeIds);
        }

        return self::$salableUsedProductsCache[$product->getId()];
    }

    protected function optimizedGetSalableUsedProducts(\Magento\Catalog\Model\Product $product, $requiredAttributeIds = null) {
        $usedProducts = $this->getUsedProducts($product, $requiredAttributeIds);

        $usedProductsIds = array_map(function($product) {
            return $product->getId();
        }, $usedProducts);


        /** @var \MageSuite\Frontend\Helper\Product\Stock $stockHelper */
        $stockHelper = \Magento\Framework\App\ObjectManager::getInstance()->get('\MageSuite\Frontend\Helper\Product\Stock');

        $stockStatuses = $stockHelper->getStockStatuses($usedProductsIds);

        $usedSalableProducts = array_filter($usedProducts, function (\Magento\Catalog\Model\Product $product) use($stockStatuses) {
            $stockStatus = $stockStatuses[$product->getId()];

            return (int)$stockStatus->getStockStatus() === \Magento\CatalogInventory\Model\Stock\Status::STATUS_IN_STOCK and $product->isSalable();
        });

        return $usedSalableProducts;
    }
}
