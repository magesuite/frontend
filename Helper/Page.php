<?php

namespace MageSuite\Frontend\Helper;

class Page extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var \Magento\Cms\Helper\Page
     */
    private $pageHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    private $pageCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Helper\Page $pageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory
    )
    {
        parent::__construct($context);
        $this->pageHelper = $pageHelper;
        $this->storeManager = $storeManager;
        $this->pageCollectionFactory = $pageCollectionFactory;
    }

    public function getPageUrl($pageGroupIdentifier)
    {
        $storeId = $this->storeManager->getStore()->getId();

        $page = $this->getCmsPage($pageGroupIdentifier, $storeId);

        if (!$page->getId()) {
            $page = $this->getCmsPage($pageGroupIdentifier);
        }

        if (!$page->getId()) {
            return null;
        }

        return $this->pageHelper->getPageUrl($page->getId());
    }

    private function getCmsPage($pageGroupIdentifier, $storeId = null)
    {
        $collection = $this->pageCollectionFactory->create();
        $collection->addFieldToFilter('page_group_identifier', $pageGroupIdentifier);
        if (!empty($storeId)) {
            $collection->addFieldToFilter('store_id', $storeId);
        }
        $collection->addFieldToSelect(['identifier', 'page_id']);

        return $collection->getFirstItem();
    }

}
