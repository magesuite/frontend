<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Magento\Cms\Model\Wysiwyg\Images\Storage;

class AllowUploadingAdditionalFileTypes
{
    protected const ADDITIONAL_FILE_TYPES = ['svg', 'webp', 'webm'];
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

        if (in_array($fileExtension, self::ADDITIONAL_FILE_TYPES)) {
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

        if (in_array($fileExtension, self::ADDITIONAL_FILE_TYPES)) {
            $mediaRootDir = $this->directory->getAbsolutePath();

            if (str_starts_with($filePath, $mediaRootDir)) {
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

        return !in_array($fileExtension, self::ADDITIONAL_FILE_TYPES) ? $proceed($source, $keepRation) : false;
    }
}
