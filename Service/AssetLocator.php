<?php

namespace MageSuite\Frontend\Service;

interface AssetLocator
{
    /**
     * Gets URL of an asset
     * @param string $assetLocation
     * @return mixed
     */
    public function getUrl(string $assetLocation);
}
