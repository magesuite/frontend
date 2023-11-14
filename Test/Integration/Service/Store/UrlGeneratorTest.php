<?php

namespace MageSuite\Frontend\Test\Integration\Service\Store;

use Magento\TestFramework\Helper\Bootstrap;

class UrlGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\Frontend\Service\Store\UrlGenerator;
     */
    private $urlGenerator;

    /**
     * @var \Magento\Store\Model\Store
     */
    private $store;

    public function setUp()
    {
        $this->urlGenerator = Bootstrap::getObjectManager()->create('MageSuite\Frontend\Service\Store\UrlGenerator');
        $this->store = Bootstrap::getObjectManager()->create('Magento\Store\Model\Store');
    }

    public static function loadPagesFixture()
    {
        require __DIR__ . '/../../_files/pages.php';
    }

    public static function loadPagesFixtureRollback()
    {
        require __DIR__ . '/../../_files/pages_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture loadPagesFixture
     */
    public function testCorrectGenerated()
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
     * @magentoDataFixture loadPagesFixture
     */
    public function testEmptyGenerated()
    {
        $pageId = 103;
        $storeId = $this->store->load('second')->getId();
        $currentUriString = 'm2c.dev/site2-default';

        $newUrl = $this->urlGenerator->replaceCmsPageUrl($pageId, $storeId, $currentUriString);
        $this->assertEmpty($newUrl);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture loadPagesFixture
     */
    public function testHostEqualsToIdentifier()
    {
        $pageId = 104;
        $storeId = $this->store->load('second')->getId();
        $currentUriString = 'm2c.dev/m2c';
        $expectedUriString = 'm2c.dev/m2c-site-3';

        $newUrl = $this->urlGenerator->replaceCmsPageUrl($pageId, $storeId, $currentUriString);
        $this->assertEquals($expectedUriString, $newUrl);
    }
}
