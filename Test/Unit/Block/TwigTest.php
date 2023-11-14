<?php

namespace MageSuite\Frontend\Test\Unit\Block;

class TwigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Frontend\Block\Twig
     */
    private $twigBlock;

    public function setUp() {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->twigBlock = $this->objectManager->get(\MageSuite\Frontend\Block\Twig::class);
    }

    public function testItImplementsBlockInterface() {
        $this->assertInstanceOf(\Magento\Framework\View\Element\BlockInterface::class, $this->twigBlock);
    }

    /**
     * @dataProvider getTitles
     */
    public function testItDisplaysTemplate($title) {
        $locatorStub = $this
            ->getMockBuilder(\MageSuite\Frontend\Template\Locator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twigBlock = $this->objectManager->create(
            \MageSuite\Frontend\Block\Twig::class,
            [
                'locator' => $locatorStub,
                'data' => ['title' => $title]
            ]
        );

        $locatorStub->method('locate')->willReturn(__DIR__.'/assets/template.twig');

        $this->assertEquals($title, $twigBlock->toHtml());
    }
    public static function getTitles() {
        return [
            ['example content'],
            ['another content']
        ];
    }
}
