<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class OptimizeTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\MageSuite\Frontend\Helper\Optimize $optimizeHelper;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->optimizeHelper = $this->objectManager->get(\MageSuite\Frontend\Helper\Optimize::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoConfigFixture current_store google/optimize/optimize_container_id test123
     */
    public function testItReturnCorrectValue()
    {
        $this->assertEquals('test123', $this->optimizeHelper->getOptimizeContainerId());
    }

}
