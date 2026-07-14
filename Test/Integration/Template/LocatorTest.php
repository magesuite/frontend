<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Template;

class LocatorTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\Frontend\Template\Locator $locator;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->locator = $this->objectManager->get(\MageSuite\Frontend\Template\Locator::class);
    }

    /**
     * @magentoAppArea frontend
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('getPaths')]
    public function testItReturnsCorrectTemplatePath(string $locatorPath, string $expectedPath): void
    {
        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->$assertContains($expectedPath, $this->locator->locate($locatorPath));
    }

    public static function getPaths(): array
    {
        return [
            ['Magento_Theme::template.phtml', 'view/frontend/templates/template.phtml']
        ];
    }

}
