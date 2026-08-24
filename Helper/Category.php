<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Helper;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected const CACHE_LIFETIME = 86400;
    protected const CACHE_TAG = 'layered_navigation_tree_%s_%s_%s';

    public const CATEGORY_CUSTOM_URL = 'category_custom_url';
    protected const CATEGORY_TOP_LEVEL = 2;

    protected const XML_PATH_SEO_CATEGORY_CUSTOM_URL_REDIRECTION_TYPE = 'seo/category/custom_url_redirection_type';

    protected int $rootCategoryId = 0;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        protected \Magento\Framework\Registry $registry,
        protected \MageSuite\Frontend\Model\Category\Tree $categoryTree,
        protected \MageSuite\ContentConstructorFrontend\DataProviders\ProductCarouselDataProvider $productDataProvider,
        protected \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        protected \Magento\Framework\App\CacheInterface $cache,
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer
    ) {
        parent::__construct($context);
    }

    public function getCustomUrlRedirectionType(): int
    {
        $value = (int)$this->scopeConfig->getValue(self::XML_PATH_SEO_CATEGORY_CUSTOM_URL_REDIRECTION_TYPE);

        if (empty($value)) {
            return \Magento\UrlRewrite\Model\OptionProvider::TEMPORARY;
        }

        return $value;
    }

    public function getCategoryNode($category = null, $returnCurrent = false)
    {
        if (!$category) {
            $category = $this->registry->registry('current_category');

            if (!$category) {
                return false;
            }
        }

        $cacheTag = sprintf(self::CACHE_TAG, $category->getId(), (int)$returnCurrent, $this->storeManager->getStore()->getId());

        try {
            $categoryNode = $this->serializer->unserialize($this->cache->load($cacheTag));
        } catch (\InvalidArgumentException $exception) {
            $categoryNode = null;
        }

        if (!$categoryNode) {
            $configuration = [
                'root_category_id' => $this->getRootCategoryId(),
                'only_included_in_menu' => 0
            ];

            $categoryTreeId = ($returnCurrent or $category->getLevel() == self::CATEGORY_TOP_LEVEL) ? $category->getId() : $category->getParentId();
            $categoryNode = $this->categoryTree->getCategoryTree($configuration, (int)$categoryTreeId);

            if (!$categoryNode) {
                return false;
            }

            $this->cache->save(
                $this->serializer->serialize($categoryNode),
                $cacheTag,
                [\Magento\Catalog\Model\Category::CACHE_TAG, 'layered_navigation_tree'],
                self::CACHE_LIFETIME
            );
        }

        $categoryNode['current'] = true;
        if ($category->getLevel() > self::CATEGORY_TOP_LEVEL) {
            $categoryNode['children'][$category->getId()]['current'] = true;
        }

        return $categoryNode;
    }

    protected function getFeaturedProductsIds($category)
    {
        $featuredProducts = $category->getFeaturedProducts();

        if ($featuredProducts == '{}') {
            $featuredProducts = $this->categoryResource
                ->getAttributeRawValue($category->getId(), 'featured_products', 0);
        }

        if (!$featuredProducts or $featuredProducts == '{}') {
            return [];
        }

        return array_keys($this->jsonDecoder->decode($featuredProducts));
    }

    public function getFeaturedProducts($category)
    {
        $featuredProductsIds = $this->getFeaturedProductsIds($category);

        if (empty($featuredProductsIds)) {
            return [];
        }

        $criteria = ['product_ids' => $featuredProductsIds];
        $products = $this->productDataProvider->getProducts($criteria);

        return $products;
    }

    public function prepareCategoryCustomUrl($customUrl)
    {
        if (!$customUrl) {
            return null;
        }

        if (strpos($customUrl, 'http') !== false) {
            return $customUrl;
        }

        $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        return $baseUrl . ltrim($customUrl, '/');
    }

    public function getImageTeaser($category)
    {
        $url = false;
        $image = is_object($category) ? $category->getImageTeaser() : $category;

        if ($image) {
            if (is_string($image)) {
                $url = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'catalog/category/' . $image;
            } elseif (is_array($image) && isset($image[0]) && isset($image[0]['name'])) {
                $url = $this->storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . 'catalog/category/' . $image[0]['name'];
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }

        return $url;
    }

    protected function getRootCategoryId(): int
    {
        if (empty($this->rootCategoryId)) {
            $this->rootCategoryId = (int)$this->storeManager->getStore()->getRootCategoryId();
        }

        return $this->rootCategoryId;
    }
}
