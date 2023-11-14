<?php

namespace MageSuite\Frontend\Plugin;

class OptimizeWysiwygImage
{
    /**
     * @var \MageSuite\ImageOptimization\Service\Image\Optimizer
     */
    private $imageOptimizer;

    public function __construct(\MageSuite\ImageOptimization\Service\Image\Optimizer $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    public function beforeResizeFile(\Magento\Cms\Model\Wysiwyg\Images\Storage $subject, $source, $keepRation = true) {
        //$this->imageOptimizer->optimize($source);
    }
}
