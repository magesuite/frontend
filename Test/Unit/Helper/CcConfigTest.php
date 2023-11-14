<?php

namespace MageSuite\Frontend\Test\Unit\Helper;

class CcConfigTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\ContentConstructorAdmin\DataProviders\ContentConstructorConfigDataProvider::class|PHPUnit_Framework_MockObject_MockObject
     */
    protected $configDataProviderStub;

    /**
     * @var \MageSuite\Frontend\Helper\CcConfig
     */
    protected $ccConfigHelper;

    /**
     * @var array
     */
    protected static $ccConfig = [
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

    public function setUp()
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

    public static function expectedScenarios() {
        return [
            [json_encode(self::$ccConfig),true,'{"phone":1,"phoneLg":2,"tablet":3,"laptop":4,"laptopLg":4,"desktop":4,"tv":4}'],
            [json_encode(self::$ccConfig),false,'{"phone":1,"phoneLg":2,"tablet":2,"laptop":3,"laptopLg":3,"desktop":3,"tv":3}'],
            ['{}',true,'{}']
        ];
    }

    /**
     * @dataProvider expectedScenarios
     */
    public function testItReturnsCorrectColumnConfiguration($ccConfig, $isFullWidth, $expectedConfiguration)
    {
        $this->configDataProviderStub->method('getConfig')->willReturn($ccConfig);

        $configuration = $this->ccConfigHelper->getColumnsConfiguration($isFullWidth);

        $this->assertEquals($expectedConfiguration, $configuration);
    }
}
