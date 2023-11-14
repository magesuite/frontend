<?php

namespace MageSuite\Frontend\Test\Unit\Plugin;

class SwitchStoreViewReplaceUrlTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\Frontend\Plugin\SwitchStoreViewReplaceUrl
     */
    protected $plugin;

    /**
     * @var \Magento\Store\Block\Switcher|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $switcherDummy;

    /**
     * @var \Magento\Framework\App\Request\Http|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestDouble;

    /**
     * @var \Magento\Store\Model\Store|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeDouble;

    /**
     * @var \MageSuite\Frontend\Service\Store\UrlGenerator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlGeneratorDouble;

    /**
     * @var \Magento\Framework\Url\Helper\Data|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlHelperDouble;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->switcherDummy = $this->getMockBuilder(\Magento\Store\Block\Switcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestDouble = $this->getMockBuilder(\Magento\Framework\App\Request\Http::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeDouble = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlGeneratorDouble = $this->getMockBuilder(\MageSuite\Frontend\Service\Store\UrlGenerator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlHelperDouble = $this->getMockBuilder(\Magento\Framework\Url\Helper\Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = $this->objectManager->create(
            \MageSuite\Frontend\Plugin\SwitchStoreViewReplaceUrl::class,
            [
                'request' => $this->requestDouble,
                'urlHelper' => $this->urlHelperDouble,
                'urlGenerator' => $this->urlGeneratorDouble
            ]
        );
    }

    public function testReturnEncodedData()
    {
        $this->requestDouble
            ->expects($this->once())
            ->method('getFullActionName')
            ->willReturn('cms_page_view');

        $this->requestDouble
            ->expects($this->once())
            ->method('getParam')
            ->with('page_id')
            ->willReturn(100);

        $this->requestDouble
            ->expects($this->once())
            ->method('getUriString')
            ->willReturn('m2c.dev/site1-default');

        $this->storeDouble
            ->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $this->urlGeneratorDouble
            ->expects($this->once())
            ->method('replaceCmsPageUrl')
            ->with(100, 1, 'm2c.dev/site1-default')
            ->willReturn('m2c.dev/site1-second');

        $this->urlHelperDouble
            ->expects($this->once())
            ->method('getEncodedUrl')
            ->with('m2c.dev/site1-second')
            ->willReturn('J20yYy5kZXYvc2l0ZTEtc2Vjb25kJw==');

        $result = $this->plugin->afterGetTargetStorePostData($this->switcherDummy, '', $this->storeDouble, []);

        $this->assertEquals(
            '{"action":"m2c.dev\/site1-second","data":{"uenc":"J20yYy5kZXYvc2l0ZTEtc2Vjb25kJw==","___store":null}}',
            $result
        );
    }
}
