<?php

namespace MageSuite\Frontend\Plugin;

use Magento\Framework\App\Filesystem\DirectoryList;

class ResizeWysiwygImage
{

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

    public function beforeResizeFile(\Magento\Cms\Model\Wysiwyg\Images\Storage $subject, $source, $keepRation = true)
    {
        $this->resizer->createThumbs($source);

        return [$source, $keepRation];
    }

}
