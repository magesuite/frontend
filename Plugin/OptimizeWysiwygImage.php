<?php

namespace MageSuite\Frontend\Plugin;

class OptimizeWysiwygImage
{
    /**
     * @var \MageSuite\Frontend\Service\Image\Optimizer
     */
    private $imageOptimizer;

    public function __construct(\MageSuite\Frontend\Service\Image\Optimizer $imageOptimizer)
    {
        $this->imageOptimizer = $imageOptimizer;
    }

    public function beforeResizeFile(\Magento\Cms\Model\Wysiwyg\Images\Storage $subject, $source, $keepRation = true) {
        //$this->imageOptimizer->optimize($source);
    }
}
