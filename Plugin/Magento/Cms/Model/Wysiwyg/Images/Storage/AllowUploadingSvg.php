<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Magento\Cms\Model\Wysiwyg\Images\Storage;

class AllowUploadingSvg
{
    protected const SVG_FILE_EXTENSION = 'svg';
    protected \Magento\Framework\Filesystem\Directory\ReadInterface $directory;

    public function __construct(
        protected \Magento\Cms\Helper\Wysiwyg\Images $cmsWysiwygImages,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->directory = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    public function aroundGetThumbnailPath(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $filePath,
        $checkFile = false
    ) {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($fileExtension == self::SVG_FILE_EXTENSION) {
            return false;
        }

        return $proceed($filePath, $checkFile);
    }

    public function aroundGetThumbnailUrl(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $filePath,
        $checkFile = false
    ) {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($fileExtension == self::SVG_FILE_EXTENSION) {
            $mediaRootDir = $this->directory->getAbsolutePath();

            if (strpos($filePath, $mediaRootDir) === 0) {
                return str_replace(
                    '\\',
                    '/',
                    $this->cmsWysiwygImages->getBaseUrl() . substr($filePath, strlen($mediaRootDir))
                );
            }

            return false;
        }

        return $proceed($filePath, $checkFile);
    }

    public function aroundResizeFile(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $source,
        $keepRation = true
    ) {
        $fileExtension = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        return $fileExtension !== self::SVG_FILE_EXTENSION
            ? $proceed($source, $keepRation)
            : false;
    }
}
