<?php

namespace MageSuite\Frontend\Service\Image;


class Resizer
{

    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    private $imageAdapterFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \MageSuite\Frontend\Service\Image\Optimizer
     */
    private $imageOptimizer;

    protected $thumbsDirectory = '/.thumbs/{width}';

    const WIDTHS_DEFAULT = [480, 768, 1024, 1440, 1920];
    const WIDTHS_CATEGORY = [480, 960];


    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Image\AdapterFactory $imageAdapterFactory,
        \MageSuite\Frontend\Service\Image\Optimizer $imageOptimizer
    )
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->imageAdapterFactory = $imageAdapterFactory;
        $this->imageOptimizer = $imageOptimizer;
    }

    public function createThumbs($source, $type = null)
    {
        $directory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::ROOT);
        $realPath = $directory->getAbsolutePath($source);
        if (!$directory->isExist($realPath) || !$directory->isFile($realPath)) {
            return false;
        }

        try {
            list($originalImageWidth) = getimagesize($source);
        } catch (\Exception $e) {
            $message = sprintf('Problem with image: %s, Error message: %s', $source, $e->getMessage());
            $this->logger->warning($message);
            return false;
        }

        $imageDirectory = dirname($realPath);

        switch ($type) {
            case 'category':
                $widths = self::WIDTHS_CATEGORY;
                break;
            default:
                $widths = self::WIDTHS_DEFAULT;
                break;
        }
        foreach ($widths as $targetWidth) {
            if ($targetWidth >= $originalImageWidth) {
                continue;
            }

            $targetDirectory = $imageDirectory . str_replace('{width}', $targetWidth, $this->thumbsDirectory);
            $targetFilePath = $targetDirectory . '/' . pathinfo($source, PATHINFO_BASENAME);

            if (!file_exists($targetDirectory)) {
                mkdir($targetDirectory, 0777, true);
            }

            $this->resizeImage($realPath, $targetWidth, $targetFilePath);
            //$this->imageOptimizer->optimize($targetFilePath);
        }
    }

    /**
     * @param $source
     * @param $width
     * @param $targetFilePath
     */
    public function resizeImage($sourceFilePath, $targetWidth, $targetFilePath)
    {
        $image = $this->imageAdapterFactory->create();

        $image->open($sourceFilePath);
        $image->resize($targetWidth);
        $image->save($targetFilePath);
    }
}
