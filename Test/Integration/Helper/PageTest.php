<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class PageTest extends \PHPUnit\Framework\TestCase
{
    protected \Magento\TestFramework\ObjectManager $objectManager;
    protected \MageSuite\Frontend\Helper\Page $pageHelper;
    protected \Magento\Store\Model\Store $store;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->store = $this->objectManager->create('Magento\Store\Model\Store');

        $this->pageHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Page::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/Helper/_files/pages.php
     */
    public function testItReturnCorrectUrl(): void
    {
        $expectedResults = [
            'site1' => 'http://localhost/index.php/site1-default',
            'site2' => 'http://localhost/index.php/site2-all',
            'site3' => null,
        ];

        foreach ($expectedResults as $pageGroupIdentifier => $expectedResult) {
            $result = $this->pageHelper->getPageUrl($pageGroupIdentifier);
            $this->assertEquals($expectedResult, $result);
        }
    }
}
