<?php

namespace MageSuite\Frontend\Test\Integration\Template;

class LocatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Frontend\Template\Locator
     */
    private $locator;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->locator = $this->objectManager->get(\MageSuite\Frontend\Template\Locator::class);
    }

    /**
     * @magentoAppArea frontend
     * @dataProvider getPaths
     */
    public function testItReturnsCorrectTemplatePath($locatorPath, $expectedPath)
    {
        $this->assertContains($expectedPath, $this->locator->locate($locatorPath));
    }

    public static function getPaths()
    {
        return [
            ['Magento_Theme::template.phtml', 'view/frontend/templates/template.phtml']
        ];
    }

}
