<?php

namespace MageSuite\Frontend\Helper;

class CcConfig extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \MageSuite\ContentConstructorAdmin\DataProviders\ContentConstructorConfigDataProvider
     */
    protected $contentConstructorConfigDataProvider;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MageSuite\ContentConstructorAdmin\DataProviders\ContentConstructorConfigDataProvider $contentConstructorConfigDataProvider
    )
    {
        parent::__construct($context);

        $this->contentConstructorConfigDataProvider = $contentConstructorConfigDataProvider;
    }

    public function getColumnsConfiguration($isFullWidth = false) {
        $ccConfig = json_decode($this->contentConstructorConfigDataProvider->getConfig(), true);

        $configType = $isFullWidth ? 'full' : 'withSidebar';

        $columnsConfig = isset($ccConfig['columnsConfig'][$configType]) ? $ccConfig['columnsConfig'][$configType] : new \stdClass();

        return json_encode($columnsConfig);
    }
}
