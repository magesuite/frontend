<?php

namespace MageSuite\Frontend\Test\Unit\Block\Product\View;

use Magento\Catalog\Api\Data\ProductInterface;

class TileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;


    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();
    }

    public function testItReturnsCorrectCacheKey()
    {
        $block = $this->getTileBlock();

        $block->setCacheKeyElements('first_additional_cache_value', 'second_additional_cache_value');

        $this->assertStringStartsWith('product_tile_222', $block->getCacheKey());
    }

    public function testItReturnsCorrectIdentities()
    {
        $block = $this->getTileBlock();

        $this->assertEquals(['cat_p_222'], $block->getIdentities());
    }

    public function testItReturnsCorrectTemplate()
    {
        $block = $this->getTileBlock();

        $this->assertEquals('product/tile.phtml', $block->getTemplate());
    }

    /**
     * @return mixed
     */
    protected function getTileBlock()
    {
        $block = $this->objectManager->create(
            \MageSuite\Frontend\Block\Product\View\Tile::class,
            [
                'data' => [
                    'product' => $this->getProductFixture()
                ]
            ]
        );

        return $block;
    }

    protected function getProductFixture()
    {
        $productMock = $this->createMock(\Magento\Catalog\Model\Product::class);

        $productMock->method('getId')->willReturn(222);
        $productMock->method('getIdentities')->willReturn(['cat_p_222']);

        $productMock->method('getData')->with('special_price')->willReturn(19);

        return $productMock;
    }
}
