<?php

namespace MageSuite\Frontend\Model\Product;

use Magento\Catalog\Model\Product\Image\NotLoadInfoImageException;

class Image extends \Magento\Catalog\Model\Product\Image
{
    /**
     * @param  string|null $file
     * @return boolean
     */
    protected function _checkMemory($file = null)
    {
        return true;
    }

    /**
     * Return resized product image information
     *
     * @return array
     * @throws
     */
    public function getResizedImageInfo()
    {
        try {
            $fileInfo = null;
            if ($this->_newFile === true) {
                $asset    = $this->_assetRepo->createAsset(
                    "Magento_Catalog::images/product/placeholder/" . $this->getDestinationSubdir(). ".jpg"
                );
                $image    = $asset->getSourceFile();
                $fileInfo = $this->getImageSize($image);
            } else {
                $fileInfo = $this->getImageSize($this->_mediaDirectory->getAbsolutePath($this->_newFile));
            }

            return $fileInfo;
        } finally {
            if (empty($fileInfo)) {
                throw new NotLoadInfoImageException(
                    __('Can\'t get information about the picture: %1', ($image ?? 'unknown'))
                );
            }
        }

    }

    /**
     * @param $img
     * @return array|false|mixed
     */
    protected function getImageSize($img)
    {
        $cacheKey = 'image_'.md5($img);

        $imageSize = unserialize($this->_cacheManager->load($cacheKey));

        if (!$imageSize) {
            $imageSize = getimagesize($img);

            $this->_cacheManager->save(serialize($imageSize), $cacheKey);
        }

        return $imageSize;
    }
}
