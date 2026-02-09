<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Test\Integration\Helper;

/**
 * @magentoDbIsolation enabled
 * @magentoAppIsolation enabled
 */
class OrderPrintTest extends \Magento\TestFramework\TestCase\AbstractController
{
    const ORDER_VIEW_URL = 'sales/order/view/order_id/%s';
    const SHIPMENT_VIEW_URL = 'sales/order/shipment/order_id/%s';

    protected ?\Magento\Customer\Model\Customer $customer;
    protected ?\Magento\Customer\Model\Session $customerSession;
    protected ?\Magento\Sales\Api\Data\OrderInterface $order;
    protected ?\Magento\Framework\Registry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        $this->customer = $objectManager->get(\Magento\Customer\Model\Customer::class);
        $this->customerSession = $objectManager->get(\Magento\Customer\Model\Session::class);
        $this->order = $objectManager->get(\Magento\Sales\Api\Data\OrderInterface::class);
        $this->registry = $objectManager->get(\Magento\Framework\Registry::class);
    }

    /**
     * @magentoAppArea frontend
     * @magentoConfigFixture current_store sales/general/show_print_button 0
     * @magentoDataFixture Magento/Sales/_files/order_with_customer.php
     */
    public function testPrintOrderButtonIsHiddenOnOrderAndShipmentPages()
    {
        $this->customerSession->setCustomerAsLoggedIn($this->getCustomer());
        $orderViewUrl = sprintf(self::ORDER_VIEW_URL, $this->getOrderId());
        $shipmentViewUrl = sprintf(self::SHIPMENT_VIEW_URL, $this->getOrderId());

        $assertNotContains = method_exists($this, 'assertStringNotContainsString') ? 'assertStringNotContainsString' : 'assertNotContains';

        $this->dispatch($orderViewUrl);
        $this->$assertNotContains('Print Order', $this->getResponse()->getBody());

        if ($this->registry->registry('current_order')) {
            $this->registry->unregister('current_order');
        }

        $this->dispatch($shipmentViewUrl);
        $this->$assertNotContains('Print Order', $this->getResponse()->getBody());
    }

    /**
     * @magentoAppArea frontend
     * @magentoConfigFixture current_store sales/general/show_print_button 1
     * @magentoDataFixture Magento/Sales/_files/order_with_customer.php
     */
    public function testPrintOrderButtonIsVisibleOnOrderAndShipmentPages()
    {
        $this->customerSession->setCustomerAsLoggedIn($this->getCustomer());
        $orderViewUrl = sprintf(self::ORDER_VIEW_URL, $this->getOrderId());
        $shipmentViewUrl = sprintf(self::SHIPMENT_VIEW_URL, $this->getOrderId());

        $assertContains = method_exists($this, 'assertStringContainsString') ? 'assertStringContainsString' : 'assertContains';

        $this->dispatch($orderViewUrl);
        $this->$assertContains('Print Order', $this->getResponse()->getBody());

        if ($this->registry->registry('current_order')) {
            $this->registry->unregister('current_order');
        }

        $this->dispatch($shipmentViewUrl);
        $this->$assertContains('Print Order', $this->getResponse()->getBody());
    }

    protected function getOrderId()
    {
        $order = $this->order->loadByIncrementId('100000001');

        return $order->getId();
    }

    protected function getCustomer()
    {
        return $this->customer->load(1);
    }
}
