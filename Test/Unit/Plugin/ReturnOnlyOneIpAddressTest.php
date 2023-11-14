<?php

namespace MageSuite\Frontend\Test\Unit\Plugin;

class ReturnOnlyOneIpAddressTest extends \PHPUnit\Framework\TestCase
{
    /** * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\Frontend\Plugin\ReturnOnlyOneIpAddress
     */
    protected $plugin;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddressDummy;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->plugin = $this->objectManager->create(\MageSuite\Frontend\Plugin\ReturnOnlyOneIpAddress::class);

        $this->remoteAddressDummy = $this->getMockBuilder(\Magento\Framework\HTTP\PhpEnvironment\RemoteAddress::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testItReturnsOnlyOneIpAddress()
    {
        $this->assertEquals('194.204.152.34', $this->plugin->afterGetRemoteAddress($this->remoteAddressDummy, '194.204.152.34'));
        $this->assertEquals('194.204.152.34', $this->plugin->afterGetRemoteAddress($this->remoteAddressDummy, '194.204.152.34, 127.0.0.1'));
    }
}
