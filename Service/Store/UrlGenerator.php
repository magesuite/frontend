<?php

namespace MageSuite\Frontend\Service\Store;

class UrlGenerator
{

    /**
     * @var \Magento\Cms\Model\PageFactory
     */
    private $pageFactory;

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    private $pageCollectionFactory;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    private $urlHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->pageFactory = $pageFactory;
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->urlHelper = $urlHelper;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int $pageId
     * @param int $storeId
     * @param string $currentUriString
     * @return string|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function replaceCmsPageUrl($pageId, $storeId, $currentUriString)
    {
        $newUrl = null;
        $currentPage = $this->pageFactory->create()->load($pageId);

        if (empty($currentPage) or !$currentPage->getPageGroupIdentifier()) {
            return null;
        }

        $targetPage = $this->getCmsPage($storeId, $currentPage->getPageGroupIdentifier());

        if (!$targetPage->getId()) {
            return null;
        }

        $newUrl = $this->replaceLastMatch($currentPage->getIdentifier(), $targetPage->getIdentifier(), $currentUriString);

        $currentStoreUrl = $this->storeManager->getStore()->getUrl();
        $targetStoreUrl = $this->storeManager->getStore($storeId)->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);

        $newUrl = $this->replaceLastMatch($currentStoreUrl, $targetStoreUrl, $newUrl);

        return $newUrl;
    }

    public function getCmsPage($storeId, $pageGroupIdentifier)
    {
        $collection = $this->pageCollectionFactory->create();
        $collection->addFieldToFilter('page_group_identifier', $pageGroupIdentifier);
        $collection->addFieldToFilter('store_id', $storeId);
        $collection->addFieldToSelect(['identifier', 'page_id']);

        return $collection->getFirstItem();
    }

    private function replaceLastMatch($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

}
