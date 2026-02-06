<?php

namespace MageSuite\Frontend\Test\Unit\Helper;

class CcConfigTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\Frontend\Helper\CcConfig $ccConfigHelper;

    /**
     * @var \MageSuite\ContentConstructorAdmin\DataProviders\ContentConstructorConfigDataProvider
     */
    protected ?\PHPUnit\Framework\MockObject\MockObject $configDataProviderStub;

    protected static array $ccConfig = [
        'columnsConfig' => [
            'full' => [
                'phone' => 1,
                'phoneLg' => 2,
                'tablet' => 3,
                'laptop' => 4,
                'laptopLg' => 4,
                'desktop' => 4,
                'tv' => 4,
            ],
            'withSidebar' => [
                'phone' => 1,
                'phoneLg' => 2,
                'tablet' => 2,
                'laptop' => 3,
                'laptopLg' => 3,
                'desktop' => 3,
                'tv' => 3,
            ]
        ]
    ];

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->configDataProviderStub = $this->getMockBuilder(\MageSuite\ContentConstructorAdmin\DataProviders\ContentConstructorConfigDataProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->ccConfigHelper = $this->objectManager->create(
            \MageSuite\Frontend\Helper\CcConfig::class,
            ['contentConstructorConfigDataProvider' => $this->configDataProviderStub]
        );
    }

    /**
     * @dataProvider expectedScenarios
     */
    public function testItReturnsCorrectColumnConfiguration(string $ccConfig, bool $isFullWidth, string $expectedConfiguration): void
    {
        $this->configDataProviderStub->method('getConfig')->willReturn($ccConfig);

        $configuration = $this->ccConfigHelper->getColumnsConfiguration($isFullWidth);

        $this->assertEquals($expectedConfiguration, $configuration);
    }

    public static function expectedScenarios(): array
    {
        return [
            [json_encode(self::$ccConfig), true, '{"phone":1,"phoneLg":2,"tablet":3,"laptop":4,"laptopLg":4,"desktop":4,"tv":4}'],
            [json_encode(self::$ccConfig), false, '{"phone":1,"phoneLg":2,"tablet":2,"laptop":3,"laptopLg":3,"desktop":3,"tv":3}'],
            ['{}', true, '{}']
        ];
    }
}
