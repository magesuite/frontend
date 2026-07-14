<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Plugin\Framework\View\Asset\File;

class AppendTimestampToAssetUrlTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\Magento\Framework\App\Config\Storage\WriterInterface $configWriter;
    protected ?\Magento\Framework\View\Asset\Repository $assetRepository;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->configWriter = $this->objectManager->create(\Magento\Framework\App\Config\Storage\WriterInterface::class);
        $this->assetRepository = $this->objectManager->create(\Magento\Framework\View\Asset\Repository::class);
    }

    /**
     * @magentoAppIsolation enabled
     * @magentoAppArea frontend
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('cases')]
    public function testItGeneratesCorrectUrl($timestamp, $file, $expectedUrl) {
        if($timestamp > 0) {
            $this->configWriter->save(
                \MageSuite\Frontend\Helper\Configuration::XML_PATH_ASSETS_URL_TIMESTAMP,
                $timestamp
            );
        }
        else {
            $this->configWriter->delete(\MageSuite\Frontend\Helper\Configuration::XML_PATH_ASSETS_URL_TIMESTAMP);
        }

        $file = $this->assetRepository->createAsset($file);

        /**
         * Legacy PHPUnit version support
         * @see https://github.com/sebastianbergmann/phpunit/issues/4086
         */
        $assertRegExp = method_exists($this, 'assertMatchesRegularExpression') ? 'assertMatchesRegularExpression' : 'assertRegExp';

        $this->$assertRegExp(
            $this->escapeRegEx($expectedUrl),
            $file->getUrl()
        );
    }

    public static function cases(): array
    {
        return [
            'js_file_with_timestamp' => [
                1634822082,
                'require/requirejs.js',
                '/static/version([0-9]+)/frontend/Magento/luma/en_US/require/requirejs.js\?t=1634822082'
            ],
            'css_file_with_timestamp' => [
                1634822082,
                'style.css',
                '/static/version([0-9]+)/frontend/Magento/luma/en_US/style.css\?t=1634822082'
            ],
            'js_file_without_timestamp' => [
                0,
                'require/requirejs.js',
                '/static/version([0-9]+)/frontend/Magento/luma/en_US/require/requirejs.js'
            ],
            'css_file_without_timestamp' => [
                0,
                'style.css',
                '/static/version([0-9]+)/frontend/Magento/luma/en_US/style.css'
            ],
            'image_without_timestamp' => [
                1634822082,
                'image.jpg',
                '/static/version([0-9]+)/frontend/Magento/luma/en_US/image.jpg'
            ],
        ];
    }

    public function escapeRegEx($regEx) {
        return sprintf('/%s/', str_replace('/', '\/', $regEx));
    }
}
