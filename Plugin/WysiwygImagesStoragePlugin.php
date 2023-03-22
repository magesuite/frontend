<?php
/**
 * @author    Marek Zabrowarny <marek.zabrowarny@creativestyle.pl>
 * @copyright 2017 creativestyle
 */


namespace MageSuite\Frontend\Plugin;

class WysiwygImagesStoragePlugin
{
    protected const IMAGE_FILE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif'
    ];

    /**
     * @var \Magento\Cms\Helper\Wysiwyg\Images
     */
    private $cmsWysiwygImages;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    private $directory;

    /**
     * @param \Magento\Cms\Helper\Wysiwyg\Images $cmsWysiwygImages
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\Cms\Helper\Wysiwyg\Images $cmsWysiwygImages,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->cmsWysiwygImages = $cmsWysiwygImages;
        $this->directory = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    /**
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $subject
     * @param \Closure $proceed
     * @param string $filePath
     * @param bool $checkFile
     * @return bool|mixed
     */
    public function aroundGetThumbnailPath(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $filePath,
        $checkFile = false
    ) {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($fileExtension == 'svg') {
            return false;
        }
        return $proceed($filePath, $checkFile);
    }

    /**
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $subject
     * @param \Closure $proceed
     * @param string $filePath
     * @param bool $checkFile
     * @return bool|mixed
     */
    public function aroundGetThumbnailUrl(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $filePath,
        $checkFile = false
    ) {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        if ($fileExtension == 'svg') {
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

    /**
     * @param \Magento\Cms\Model\Wysiwyg\Images\Storage $subject
     * @param \Closure $proceed
     * @param string $source
     * @param bool $keepRation
     * @return bool|string
     */
    public function aroundResizeFile(
        \Magento\Cms\Model\Wysiwyg\Images\Storage $subject,
        \Closure $proceed,
        $source,
        $keepRation = true
    ) {
        $fileExtension = strtolower(pathinfo($source, PATHINFO_EXTENSION));
        return in_array($fileExtension, self::IMAGE_FILE_EXTENSIONS)
            ? $proceed($source, $keepRation)
            : false;
    }
}
