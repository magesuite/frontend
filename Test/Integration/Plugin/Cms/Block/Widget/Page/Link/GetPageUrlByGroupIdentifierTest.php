<?php

namespace MageSuite\Frontend\Test\Integration\Plugin\Cms\Block\Widget\Page\Link;

class GetPageUrlByGroupIdentifierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Cms\Block\Widget\Page\Link
     */
    protected $pageLink;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->storeManager = $this->objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $this->pageLink = $this->objectManager->get(\Magento\Cms\Block\Widget\Page\Link::class);

    }

    public static function loadPagesFixture()
    {
        require __DIR__ . '/../../../../../../_files/pages.php';
    }

    public static function loadPagesFixtureRollback()
    {
        require __DIR__ . '/../../../../../../_files/pages_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDataFixture loadPagesFixture
     */
    public function testCorrectGenerated()
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
