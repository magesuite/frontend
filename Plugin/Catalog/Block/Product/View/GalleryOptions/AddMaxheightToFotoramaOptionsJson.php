<?php

namespace MageSuite\Frontend\Plugin\Catalog\Block\Product\View\GalleryOptions;

class AddMaxheightToFotoramaOptionsJson
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    public function __construct(\Magento\Framework\Serialize\Serializer\Json $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    public function afterGetOptionsJson(\Magento\Catalog\Block\Product\View\GalleryOptions $subject, $result)
    {
        $optionItems = $this->jsonSerializer->unserialize($result);

        if (!isset($optionItems['maxheight']) && $subject->getVar("gallery/maxheight")) {
            $optionItems['maxheight'] = $subject->getVar("gallery/maxheight");
        }

        return $this->jsonSerializer->serialize($optionItems);
    }
}
