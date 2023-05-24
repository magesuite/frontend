<?php

namespace MageSuite\Frontend\Plugin\Framework\App\ProductMetadataInterface;

class CacheVersion
{
    protected static $version = null;
    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    public function __construct(\Magento\Framework\App\CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function aroundGetVersion(\Magento\Framework\App\ProductMetadataInterface $subject, callable $proceed) {
        if(self::$version == null) {
            if ((self::$version = $this->cache->load('magento_version')) == false) {
                self::$version = $proceed();

                $this->cache->save(self::$version, 'magento_version');
            }
        }

        return self::$version;
    }
}
