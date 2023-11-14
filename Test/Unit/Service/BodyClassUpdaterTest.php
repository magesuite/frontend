<?php

namespace MageSuite\Frontend\Test\Unit\Service;


class BodyClassUpdaterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\Frontend\Service\BodyClassUpdater
     */
    protected $bodyClassUpdater;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
        $this->bodyClassUpdater = $this->objectManager->get(\MageSuite\Frontend\Service\BodyClassUpdater::class);
    }

    public function testItReturnsNullIfFilterIsNotSelected()
    {
        $html = file_get_contents(__DIR__ . '/../_files/category_with_filters.html', 'r');

        $updatedHtml = $this->bodyClassUpdater->addFilterClassToBody($html, []);

        $this->assertNull($updatedHtml);
    }

    public function testItAddClassIfFilterIsSelected()
    {
        $html = file_get_contents(__DIR__ . '/../_files/category_with_filters.html', 'r');

        $updatedHtml = $this->bodyClassUpdater->addFilterClassToBody($html, ['size' => 'S']);

        $this->assertContains('<body data-container="body" class="page-with-filter page-with-active-filter page-products catalog-category-view page-layout-2columns-left">', $updatedHtml);
    }
}
