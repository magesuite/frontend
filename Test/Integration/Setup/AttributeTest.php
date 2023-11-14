<?php

namespace MageSuite\Frontend\Test\Integration\Setup;

class AttributeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $eavSetup;

    public function setUp()
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

        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'category_custom_url'));
        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'category_icon'));
        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'featured_products_header'));
        $this->assertNotEmpty($eavSetup->getAttributeId(\Magento\Catalog\Model\Category::ENTITY, 'featured_products'));
    }


}
