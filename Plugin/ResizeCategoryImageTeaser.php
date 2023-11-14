<?php

namespace MageSuite\Frontend\Plugin;


class ResizeCategoryImageTeaser
{

    const IMAGE_TEASER_CODE = 'image_teaser';

    const MEDIA_CATEGORY_PATH = 'pub/media/catalog/category';

    /**
     * @var \MageSuite\Frontend\Service\Image\Resizer
     */
    private $resizer;

    public function __construct(
        \MageSuite\Frontend\Service\Image\Resizer $resizer
    )
    {
        $this->resizer = $resizer;
    }

    public function aroundAfterSave(\Magento\Catalog\Model\Category\Attribute\Backend\Image $subject, callable $proceed, $object)
    {
        $result = $proceed($object);

        $attributeCode = $subject->getAttribute()->getAttributeCode();
        if ($attributeCode != self::IMAGE_TEASER_CODE) {
            return $result;
        }

        $value = $object->getData('_additional_data_image_teaser');
        $sourceImagePath = self::MEDIA_CATEGORY_PATH . '/' . $this->getUploadedImageName($value);

        $this->resizer->createThumbs($sourceImagePath, 'category');

        return $result;
    }

    private function getUploadedImageName($value)
    {
        if (is_array($value) && isset($value[0]['name'])) {
            return $value[0]['name'];
        }

        return '';
    }

}
