<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin;

class ResizeCategoryImageTeaser
{
    const IMAGE_TEASER_CODE = 'image_teaser';
    const MEDIA_CATEGORY_PATH = 'pub/media/catalog/category';

    protected \MageSuite\Media\Service\Thumbnail\Generator $generator;

    public function __construct(
        \MageSuite\Media\Service\Thumbnail\Generator $generator
    ) {
        $this->generator = $generator;
    }

    /**
     * @throws \Exception
     */
    public function aroundAfterSave(
        \Magento\Catalog\Model\Category\Attribute\Backend\Image $subject,
        callable $proceed,
        \Magento\Framework\DataObject $object
    ): \Magento\Catalog\Model\Category\Attribute\Backend\Image {
        $result = $proceed($object);
        $attributeCode = $subject->getAttribute()->getAttributeCode();

        if ($attributeCode != self::IMAGE_TEASER_CODE) {
            return $result;
        }

        $value = $object->getData('_additional_data_image_teaser');
        $sourceImagePath = self::MEDIA_CATEGORY_PATH . '/' . $this->getUploadedImageName($value);
        $this->generator->generateThumbnails($sourceImagePath, 'category');

        return $result;
    }

    private function getUploadedImageName($value)
    {
        return $value[0]['name'] ?? '';
    }
}
