<?php

namespace MageSuite\Frontend\Plugin;

class SwitchStoreViewReplaceUrl
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    private $urlHelper;

    /**
     * @var \MageSuite\Frontend\Service\Store\UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    private $postDataHelper;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \MageSuite\Frontend\Service\Store\UrlGenerator $urlGenerator,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper
    )
    {
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
        $this->urlHelper = $urlHelper;
        $this->postDataHelper = $postDataHelper;
    }

    public function afterGetTargetStorePostData(
        \Magento\Store\Block\Switcher $subject,
        $result,
        \Magento\Store\Model\Store $store,
        $data = []
    )
    {
        if($this->request->getFullActionName() != 'cms_page_view'){
             return $result;
        }

        $newUrl = $this->urlGenerator->replaceCmsPageUrl($this->request->getParam('page_id'), $store->getId(), $this->request->getUriString());

        if(empty($newUrl)){
            return $result;
        }

        $data[\Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED] = $this->urlHelper->getEncodedUrl($newUrl);
        $data[\Magento\Store\Api\StoreResolverInterface::PARAM_NAME] = $store->getCode();

        return $this->postDataHelper->getPostData(
            $newUrl,
            $data
        );
    }
}
