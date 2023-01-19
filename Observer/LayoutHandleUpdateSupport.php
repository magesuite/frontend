<?php

namespace MageSuite\Frontend\Observer;

class LayoutHandleUpdateSupport implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Add support for injecting layout handle updates within layout update xml
     * @see https://github.com/magento/magento2/issues/5901
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getEvent()->getLayout();

        foreach ($layout->getUpdate()->asArray() as $updateStr) {
            if (empty($updateStr)) {
                continue;
            }

            if (preg_match('~<update(.*?)handle="([a-z_-]+?)"(.*?)/>~', $updateStr, $matches)) {
                $layout->getUpdate()->addHandle($matches[2]);
            }
        }
    }
}
