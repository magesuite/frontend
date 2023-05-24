<?php

namespace MageSuite\Frontend\Service;

class MagentoAssetLocator implements AssetLocator
{
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepository;

    public function __construct(\Magento\Framework\View\Asset\Repository $assetRepository) {
        $this->assetRepository = $assetRepository;
    }

    /**
     * Returns URL of an asset.
     * @param $assetId
     * @return string
     */
    public function getUrl(string $assetLocation)
    {
        return $this
            ->assetRepository
            ->createAsset($assetLocation)
            ->getUrl();
    }
}
