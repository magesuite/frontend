<?php

namespace MageSuite\Frontend\Test\Integration\Template;

/**
 * @magentoAppIsolation enabled
 * @magentoCache all disabled
 */
class TranslatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Frontend\Template\Translator
     */
    private $translator;

    /**
     * @var \Magento\Framework\Translate
     */
    private $translate;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->translator = $this->objectManager->get(\MageSuite\Frontend\Template\Translator::class);

        $viewFileSystem = $this->createPartialMock(
            \Magento\Framework\View\FileSystem::class,
            ['getLocaleFileName']
        );

        $viewFileSystem->expects($this->any())
            ->method('getLocaleFileName')
            ->will(
                $this->returnValue(dirname(__DIR__) . '/_files/en_US.csv')
            );

        $this->objectManager->addSharedInstance($viewFileSystem, \Magento\Framework\View\FileSystem::class);

        $this->translate = $this->objectManager->create(\Magento\Framework\Translate::class);
        $this->objectManager->addSharedInstance($this->translate, \Magento\Framework\Translate::class);

        $this->objectManager->removeSharedInstance(\Magento\Framework\Phrase\Renderer\Composite::class);
        $this->objectManager->removeSharedInstance(\Magento\Framework\Phrase\Renderer\Translate::class);

        \Magento\Framework\Phrase::setRenderer(
            $this->objectManager->get(\Magento\Framework\Phrase\RendererInterface::class)
        );
    }

    /**
     * @magentoCache all disabled
     * @magentoAppArea frontend
     * @dataProvider getTexts
     */
    public function testItReturnsTranslatedText($givenText, $expectedText)
    {
        $this->translate->loadData(\Magento\Framework\App\Area::AREA_FRONTEND);
        $this->assertContains($expectedText, (string)$this->translator->translate($givenText));
    }

    public static function getTexts()
    {
        return [
            ['Design value to translate', 'Design translated value'],
            ['Translation to the same value', 'Translation to the same value'],
            ['Test with parameter %s', 'Test with parameter <div>test</div>'],
            [['Text with arguments %1 %2 %3 test %4', 'arg1', 'arg2', 'arg3', 'arg4'], 'Super test arg1, arg2, arg3, arg4']
        ];
    }

}
