<?php

namespace MageSuite\Frontend\Plugin\Cms\Block\Widget\Page\Link;

class GetPageUrlByGroupIdentifier
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageSuite\Frontend\Service\Store\UrlGenerator
     */
    protected $urlGenerator;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\Frontend\Service\Store\UrlGenerator $urlGenerator
    ){
        $this->storeManager = $storeManager;
        $this->urlGenerator = $urlGenerator;
    }

    public function afterGetHref(\Magento\Cms\Block\Widget\Page\Link $subject, $result)
    {
        if($result){
            return $result;
        }

        $pageGroupIdentifier = $subject->getData('page-group-id');

        if(!$pageGroupIdentifier){
            return $result;
        }

        $cmsPage = $this->urlGenerator->getCmsPage($this->storeManager->getStore()->getId(), $pageGroupIdentifier);

        if(!$cmsPage or !$cmsPage->getId()){
            return $result;
        }

        return $cmsPage->getIdentifier();
    }
}
