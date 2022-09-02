<?php

namespace MageSuite\Frontend\Observer\Catalog\Product;

class FullPathBreadcrumbs implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \MageSuite\Frontend\Service\Breadcrumb\BreadcrumbCategoryFinderInterface
     */
    private $breadcrumbCategoryFinder;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \MageSuite\Frontend\Service\Breadcrumb\BreadcrumbCategoryFinderInterface $breadcrumbCategoryFinder
    )
    {
        $this->registry = $registry;
        $this->breadcrumbCategoryFinder = $breadcrumbCategoryFinder;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        if ($product == null) {
            return;
        }

        $category = $this->breadcrumbCategoryFinder->getCategory($product);

        if ($category === null || $category->getId() === null) {
            return;
        }

        $this->registry->register('current_category', $category, true);
    }
}
