<?php

namespace MageSuite\Frontend\Test\Unit\Template;

class LocatorTest extends \PHPUnit\Framework\TestCase
{
    protected $customizationPathStub;

    protected $designStub;

    public function setUp()
    {
        $this->designStub = $this
            ->getMockBuilder(\Magento\Framework\View\DesignInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customizationPathStub = $this
            ->getMockBuilder(\Magento\Framework\View\Design\Theme\Customization\Path::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testItReturnsCorrectTemplatePathWhenCustomizationIsAvailable()
    {
        $this->setCurrentTheme($this->getMainTheme());

        $this->assertContains('assets/theme-main/customizations/test.twig', $this->getLocator()->locate('test.twig'));
    }

    public function testItReturnsCorrectTemplatePathWhenCustomizationIsNotAvailable()
    {
        $this->setCurrentTheme($this->getMainTheme());

        $this->assertContains('assets/theme-main/components/without_customization.twig', $this->getLocator()->locate('without_customization.twig'));
    }

    public function testItReturnsCorrectTemplatePathWhenTemplatedIsForcedToBeLoadedFromComponents()
    {
        $this->setCurrentTheme($this->getMainTheme());

        $this->assertContains('assets/theme-main/components/test.twig', $this->getLocator()->locate('components/test.twig'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItThrowsExceptionWhenTemplateWasNotFound()
    {
        $this->setCurrentTheme($this->getMainTheme());

        $this->getLocator()->locate('not_existing_template.twig');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testItThrowsExceptionWhenTemplateIsForcedToBeLoadedFromComponentsButWasNotFound()
    {
        $this->setCurrentTheme($this->getMainTheme());

        $this->getLocator()->locate('components/not_existing_template.twig');
    }

    public function testItReturnsTemplateFromCustomThemeComponents() {
        $this->setCurrentTheme($this->getCustomTheme());

        $this->assertContains('assets/theme-custom/components/custom.twig', $this->getLocator()->locate('custom.twig'));
    }

    public function testItReturnsTemplateFromCustomThemeCustomizations() {
        $this->setCurrentTheme($this->getCustomTheme());

        $this->assertContains('assets/theme-custom/customizations/customization.twig', $this->getLocator()->locate('customization.twig'));
    }

    public function testItReturnsTemplateFromFallback() {
        $this->setCurrentTheme($this->getCustomTheme());

        $this->assertContains('assets/theme-main/components/test.twig', $this->getLocator()->locate('components/test.twig'));
    }

    protected function getMainTheme() {
        $assetsLocation = realpath(__DIR__.'/assets/theme-main');

        $theme = $this->getTheme();

        $this->customizationPathStub
            ->method('getThemeFilesPath')
            ->with($theme)
            ->willReturn($assetsLocation);

        return $theme;
    }

    protected function getCustomTheme() {
        $mainAssetsLocation = realpath(__DIR__.'/assets/theme-main');
        $customAssetsLocation = realpath(__DIR__.'/assets/theme-custom');

        $mainTheme = $this->getTheme();
        $customTheme = $this->getTheme($mainTheme);

        $this->customizationPathStub
            ->method('getThemeFilesPath')
            ->will($this->onConsecutiveCalls($customAssetsLocation, $mainAssetsLocation));

        return $customTheme;
    }

    protected function getTheme($parent = null) {
        $theme = $this
            ->getMockBuilder(\Magento\Framework\View\Design\ThemeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        if($parent) {
            $theme->method('getParentTheme')->willReturn($parent);
        }

        return $theme;
    }

    protected function setCurrentTheme($themeStub) {
        $this->designStub->method('getDesignTheme')->willReturn($themeStub);
    }

    protected function getLocator() {
        $resolverDummy = $this
            ->getMockBuilder(\Magento\Framework\View\Element\Template\File\Resolver::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stateDummy = $this
            ->getMockBuilder(\Magento\Framework\App\State::class)
            ->disableOriginalConstructor()
            ->getMock();

        return new \MageSuite\Frontend\Template\Locator($resolverDummy, $stateDummy, $this->designStub, $this->customizationPathStub);
    }
}
