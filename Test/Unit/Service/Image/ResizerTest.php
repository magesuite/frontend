<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Unit\Service\Image;

class ResizerTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager = null;
    protected ?\MageSuite\Media\Service\Thumbnail\Generator $generator = null;

    protected ?array $targetWidthsDefault = [480, 768, 1024, 1440];
    protected ?array $targetWidthsCategory = [480, 960];
    protected ?string $thumbsDirectory = __DIR__ . '/../../assets/.thumbs';

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->generator = $this->objectManager->create(\MageSuite\Media\Service\Thumbnail\Generator::class);
        $this->cleanThumbsDirectory();
    }

    public function testItResizesImagesProperly()
    {
        $this->generator->generateThumbnails(realpath(__DIR__ . '/../../assets/test.jpg'));

        foreach ($this->targetWidthsDefault as $targetWidth) {
            list($resizedImageWidth) = getimagesize($this->thumbsDirectory . '/' . $targetWidth . '/test.jpg');

            $this->assertEquals($targetWidth, $resizedImageWidth);
        }
    }

    public function testItResizesImagesForCategoryProperly()
    {

        $this->generator->generateThumbnails(realpath(__DIR__ . '/../../assets/test.jpg'), 'category');

        foreach ($this->targetWidthsCategory as $targetWidth) {
            list($resizedImageWidth) = getimagesize($this->thumbsDirectory . '/' . $targetWidth . '/test.jpg');

            $this->assertEquals($targetWidth, $resizedImageWidth);
        }
    }

    public function testFileNotExist()
    {
        $result = $this->generator->generateThumbnails('non_existing_file.jpg');
        $this->assertEmpty($result);
    }

    public function cleanThumbsDirectory()
    {
        if (!file_exists($this->thumbsDirectory)) {
            return;
        }

        $this->deleteDirectory($this->thumbsDirectory);
    }

    public function deleteDirectory($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDirectory("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
