<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin;

class ResizeWysiwygImage
{
    protected \MageSuite\Media\Service\Thumbnail\Generator $generator;

    public function __construct(
        \MageSuite\Media\Service\Thumbnail\Generator $generator
    ) {
        $this->generator = $generator;
    }

    /**
     * @throws \Exception
     */
    public function beforeResizeFile(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        string $source,
        bool $keepRation = true
    ): array {
        $this->generator->generateThumbnails($source);
        return [$source, $keepRation];
    }
}
