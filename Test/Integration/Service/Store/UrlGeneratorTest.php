<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Service\Store;

class UrlGeneratorTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\Frontend\Service\Store\UrlGenerator $urlGenerator;
    protected ?\Magento\Store\Model\Store $store;

    public function setUp(): void
    {
        $this->urlGenerator = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('MageSuite\Frontend\Service\Store\UrlGenerator');
        $this->store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Store\Model\Store');
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/pages.php
     */
    public function testCorrectGenerated(): void
    {
        $pageId = 100;
        $storeId = $this->store->load('second')->getId();
        $currentUriString = 'm2c.dev/site1-default';
        $expectedUriString = 'm2c.dev/site1-second';

        $newUrl = $this->urlGenerator->replaceCmsPageUrl($pageId, $storeId, $currentUriString);
        $this->assertEquals($expectedUriString, $newUrl);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/pages.php
     */
    public function testEmptyGenerated(): void
    {
        $pageId = 103;
        $storeId = $this->store->load('second')->getId();
        $currentUriString = 'm2c.dev/site2-default';

        $newUrl = $this->urlGenerator->replaceCmsPageUrl($pageId, $storeId, $currentUriString);
        $this->assertEmpty($newUrl);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture MageSuite_Frontend::Test/Integration/_files/pages.php
     */
    public function testHostEqualsToIdentifier(): void
    {
        $pageId = 104;
        $storeId = $this->store->load('second')->getId();
        $currentUriString = 'm2c.dev/m2c';
        $expectedUriString = 'm2c.dev/m2c-site-3';

        $newUrl = $this->urlGenerator->replaceCmsPageUrl($pageId, $storeId, $currentUriString);
        $this->assertEquals($expectedUriString, $newUrl);
    }
}
