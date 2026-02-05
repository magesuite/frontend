<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Plugin\Cms\Block\Widget\Page\Link;

class GetPageUrlByGroupIdentifierTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\TestFramework\ObjectManager $objectManager;

    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected \Magento\Cms\Block\Widget\Page\Link $pageLink;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->storeManager = $this->objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $this->pageLink = $this->objectManager->get(\Magento\Cms\Block\Widget\Page\Link::class);

    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/pages.php
     */
    public function testCorrectGenerated(): void
    {
        $pageGroupId = 'site1';
        $expectedUri = [
            'default' => 'site1-default',
            'second' => 'site1-second'
        ];

        $pageLink = $this->pageLink;
        $pageLink->setData('page-group-id', $pageGroupId);

        $this->assertEquals($expectedUri['default'], $pageLink->getHref());

        $this->storeManager->setCurrentStore('second');
        $this->assertEquals($expectedUri['second'], $pageLink->getHref());
    }
}
