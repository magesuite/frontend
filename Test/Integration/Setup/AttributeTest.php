<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Setup;

class AttributeTest extends \PHPUnit\Framework\TestCase
{
    protected ?\Magento\TestFramework\ObjectManager $objectManager;
    protected ?\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory;
    protected ?\Magento\Framework\Setup\ModuleDataSetupInterface $eavSetup;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->eavSetupFactory = $this->objectManager
            ->get(\Magento\Eav\Setup\EavSetupFactory::class);

        $this->eavSetup = $this->objectManager
            ->get(\Magento\Framework\Setup\ModuleDataSetupInterface::class);
    }

    public function testItFindsAttributes()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->eavSetup]);

        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, \MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL));
        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'category_icon'));
        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'featured_products_header'));
        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'featured_products'));
    }


}
