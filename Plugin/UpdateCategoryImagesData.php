<?php

namespace MageSuite\Frontend\Plugin;

class UpdateCategoryImagesData
{
    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    public function __construct(
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->productMetadata = $productMetadata;
    }

    public function afterGetData($subject, $result)
    {
        if (version_compare($this->productMetadata->getVersion(), '2.3.0') >= 0) {
            return $result;
        }

        $categoryIds = array_keys($result);

        foreach($categoryIds AS $categoryId){
            $categoryData = $result[$categoryId];

            if(isset($categoryData['image']) AND $categoryData['image'] AND !isset($categoryData['image'][0]['name'])){
                $result[$categoryId]['image'] = null;
            }
        }

        return $result;
    }
}
