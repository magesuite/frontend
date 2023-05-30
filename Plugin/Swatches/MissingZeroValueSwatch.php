<?php

namespace MageSuite\Frontend\Plugin\Swatches;

use Magento\Swatches\Model\Swatch;

class MissingZeroValueSwatch
{
    /**
     * Default store ID
     */
    const DEFAULT_STORE_ID = 0;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory
     */
    protected $swatchCollectionFactory;


    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory $swatchCollectionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->swatchCollectionFactory = $swatchCollectionFactory;
    }

    public function aroundGetSwatchesByOptionsId(\Magento\Swatches\Helper\Data $subject, callable $proceed, array $optionIds)
    {
        /**
         * Core method contains bug which do not allow to add swatches with zero value.
         * Modification on line 39:
         * Change:
         * $item['value'] != null
         */

        /** @var \Magento\Swatches\Model\ResourceModel\Swatch\Collection $swatchCollection */
        $swatchCollection = $this->swatchCollectionFactory->create();
        $swatchCollection->addFilterByOptionsIds($optionIds);

        $swatches = [];
        $currentStoreId = $this->storeManager->getStore()->getId();
        foreach ($swatchCollection as $item) {
            if ($item['type'] != Swatch::SWATCH_TYPE_TEXTUAL) {
                $swatches[$item['option_id']] = $item->getData();
            } elseif ($item['store_id'] == $currentStoreId && $item['value'] != null) {
                $fallbackValues[$item['option_id']][$currentStoreId] = $item->getData();
            } elseif ($item['store_id'] == self::DEFAULT_STORE_ID) {
                $fallbackValues[$item['option_id']][self::DEFAULT_STORE_ID] = $item->getData();
            }
        }

        if (!empty($fallbackValues)) {
            $swatches = $this->addFallbackOptions($fallbackValues, $swatches);
        }

        return $swatches;
    }

    /**
     * @param array $fallbackValues
     * @param array $swatches
     * @return array
     */
    private function addFallbackOptions(array $fallbackValues, array $swatches)
    {
        $currentStoreId = $this->storeManager->getStore()->getId();
        foreach ($fallbackValues as $optionId => $optionsArray) {
            if (isset($optionsArray[$currentStoreId])) {
                $swatches[$optionId] = $optionsArray[$currentStoreId];
            } else {
                $swatches[$optionId] = $optionsArray[self::DEFAULT_STORE_ID];
            }
        }

        return $swatches;
    }
}
