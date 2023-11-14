<?php

namespace MageSuite\Frontend\Test\Unit\Service\Image;

class ResizerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\Frontend\Service\Image\Resizer
     */
    protected $resizer;

    protected $targetWidthsDefault= [480, 768, 1024, 1440];
    protected $targetWidthsCategory = [480, 960];

    protected $thumbsDirectory = __DIR__ . '/../../assets/.thumbs';

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->resizer = $this->objectManager->create(\MageSuite\Frontend\Service\Image\Resizer::class);

        $this->cleanThumbsDirectory();
    }

    public function testItResizesImagesProperly()
    {

        $this->resizer->createThumbs(realpath(__DIR__ . '/../../assets/test.jpg'));

        foreach ($this->targetWidthsDefault as $targetWidth) {
            list($resizedImageWidth) = getimagesize($this->thumbsDirectory . '/' . $targetWidth . '/test.jpg');

            $this->assertEquals($targetWidth, $resizedImageWidth);
        }
    }

    public function testItResizesImagesForCategoryProperly()
    {

        $this->resizer->createThumbs(realpath(__DIR__ . '/../../assets/test.jpg'), 'category');

        foreach ($this->targetWidthsCategory as $targetWidth) {
            list($resizedImageWidth) = getimagesize($this->thumbsDirectory . '/' . $targetWidth . '/test.jpg');

            $this->assertEquals($targetWidth, $resizedImageWidth);
        }
    }

    public function testFileNoExist()
    {
        $result = $this->resizer->createThumbs(realpath(__DIR__ . '/../../assets/no_exist.jpg'));

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
