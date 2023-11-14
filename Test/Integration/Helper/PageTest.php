<?php

namespace MageSuite\Frontend\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class PageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Frontend\Helper\Page
     */
    private $pageHelper;

    /**
     * @var \Magento\Store\Model\Store
     */
    private $store;


    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->store = $this->objectManager->create('Magento\Store\Model\Store');

        $this->pageHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Page::class);
    }

    public static function loadPagesFixture()
    {
        require __DIR__ . '/_files/pages.php';
    }

    public static function loadPagesFixtureRollback()
    {
        require __DIR__ . '/_files/pages_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture loadPagesFixture
     */
    public function testItReturnCorrectUrl()
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
