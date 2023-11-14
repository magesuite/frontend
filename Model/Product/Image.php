<?php

namespace MageSuite\Frontend\Model\Product;

class Image extends \Magento\Catalog\Model\Product\Image
{
    /**
     * @param string|null $file
     * @return bool
     */
    protected function _checkMemory($file = null)
    {
        return true;
    }

    /**
     * Return resized product image information
     *
     * @return array
     */
    public function getResizedImageInfo()
    {
        $fileInfo = null;
        if ($this->_newFile === true) {
            $asset = $this->_assetRepo->createAsset(
                "Magento_Catalog::images/product/placeholder/{$this->getDestinationSubdir()}.jpg"
            );
            $img = $asset->getSourceFile();
            $fileInfo = $this->getImageSize($img);
        } else {
            $fileInfo = $this->getImageSize($this->_mediaDirectory->getAbsolutePath($this->_newFile));
        }
        return $fileInfo;
    }

    protected function getImageSize($img)
    {
        $cacheKey = 'image_'.md5($img);

        $imageSize = unserialize($this->_cacheManager->load($cacheKey));

        if(!$imageSize) {
            $imageSize = @getimagesize($img);

            $this->_cacheManager->save(serialize($imageSize), $cacheKey);
        }

        return $imageSize;
    }
}
