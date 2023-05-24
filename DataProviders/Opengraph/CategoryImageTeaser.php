<?php

namespace MageSuite\Frontend\DataProviders\Opengraph;

class CategoryImageTeaser extends \MageSuite\Opengraph\DataProviders\TagProvider implements \MageSuite\Opengraph\DataProviders\TagProviderInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \MageSuite\Opengraph\Factory\TagFactoryInterface
     */
    protected $tagFactory;

    /**
     * @var \MageSuite\Opengraph\Helper\Mime
     */
    protected $mimeHelper;

    protected $tags = [];

    public function __construct(
        \Magento\Framework\Registry $registry,
        \MageSuite\Opengraph\Factory\TagFactoryInterface $tagFactory,
        \MageSuite\Opengraph\Helper\Mime $mimeHelper
    )
    {
        $this->registry = $registry;
        $this->tagFactory = $tagFactory;
        $this->mimeHelper = $mimeHelper;
    }

    public function getTags()
    {
        $category = $this->registry->registry('current_category');

        if(!$category or !$category->getId()){
            return [];
        }

        $this->addImageTag();

        return $this->tags;
    }

    private function addImageTag()
    {
        $category = $this->registry->registry('current_category');
        $imageTeaser = $category->getImageTeaser() ?? null;

        if(!$imageTeaser){
            return;
        }

        $imageUrl = $category->getImageUrl('image_teaser');

        if(!$imageUrl){
            return;
        }

        $tag = $this->tagFactory->getTag('image', $imageUrl);
        $this->addTag($tag);

        $mimeType = $this->mimeHelper->getMimeType($imageUrl);

        if($mimeType){
            $tag = $this->tagFactory->getTag('image:type', $mimeType);
            $this->addTag($tag);
        }

        $categoryData = array_filter($category->getData());
        $title = $categoryData['og_title'] ?? $categoryData['meta_title'] ?? $categoryData['name'] ?? null;

        if ($title) {
            $tag = $this->tagFactory->getTag('image:alt', $title);
            $this->addTag($tag);
        }

        return;
    }
}
