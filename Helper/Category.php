<?php

namespace MageSuite\Frontend\Helper;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CACHE_LIFETIME = 86400;
    const CACHE_TAG = 'layered_navigation_tree_%s_%s_%s';

    const CATEGORY_CUSTOM_URL = 'category_custom_url';
    const CATEGORY_TOP_LEVEL = 2;

    const XML_PATH_SEO_CATEGORY_CUSTOM_URL_REDIRECTION_TYPE = 'seo/category/custom_url_redirection_type';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \MageSuite\Frontend\Model\Category\Tree
     */
    protected $categoryTree;

    /**
     * @var \MageSuite\ContentConstructorFrontend\DataProviders\ProductCarouselDataProvider
     */
    protected $productDataProvider;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $jsonDecoder;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $categoryResource;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var \MageSuite\CategoryIcon\Helper\CategoryIcon
     */
    protected $categoryIconHelper;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    protected $rootCategoryId;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Registry $registry,
        \MageSuite\Frontend\Model\Category\Tree $categoryTree,
        \MageSuite\ContentConstructorFrontend\DataProviders\ProductCarouselDataProvider $productDataProvider,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Eav\Model\Config $eavConfig,
        \MageSuite\CategoryIcon\Helper\CategoryIcon $categoryIconHelper,
        ?\Magento\Catalog\Model\CategoryFactory $categoryFactory = null
    ) {
        parent::__construct($context);

        $this->registry = $registry;
        $this->categoryTree = $categoryTree;
        $this->productDataProvider = $productDataProvider;
        $this->jsonDecoder = $jsonDecoder;
        $this->cache = $cache;
        $this->storeManager = $storeManager;
        $this->categoryResource = $categoryResource;
        $this->categoryRepository = $categoryRepository;
        $this->eavConfig = $eavConfig;
        $this->categoryIconHelper = $categoryIconHelper;
        $this->categoryFactory = $categoryFactory
            ?? \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Catalog\Model\CategoryFactory::class);
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

        $categoryNode = unserialize($this->cache->load($cacheTag));

        if (!$categoryNode) {
            $configuration = [
                'root_category_id' => $this->getRootCategoryId(),
                'only_included_in_menu' => 0
            ];

            $categoryTreeId = ($returnCurrent or $category->getLevel() == self::CATEGORY_TOP_LEVEL) ? $category->getId() : $category->getParentId();
            $categoryNode = $this->categoryTree->getCategoryTree($configuration, $categoryTreeId);

            if (!$categoryNode) {
                return false;
            }

            $this->cache->save(serialize($categoryNode), $cacheTag, [\Magento\Catalog\Model\Category::CACHE_TAG, 'layered_navigation_tree'], self::CACHE_LIFETIME);
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

    public function getCategoryFilterIcon($filterItem)
    {
        if (!$filterItem instanceof \Smile\ElasticsuiteCatalog\Model\Layer\Filter\Item\Category) {
            return null;
        }

        $categoryId = (int)$filterItem->getValueString();
        $categoryIcon = $this->categoryResource
            ->getAttributeRawValue(
                $categoryId,
                'category_icon',
                $this->storeManager->getStore()->getId()
            );

        if (!$categoryIcon) {
            return null;
        }

        $category = $this->categoryFactory->create();
        $category->setCategoryIcon($categoryIcon);

        return $this->categoryIconHelper->getUrl($category);
    }

    public function getCategoryView()
    {
        $category = $this->registry->registry('current_category');

        if (!$category) {
            return false;
        }
        $view = $category->getCustomAttribute('category_view');

        if (!$view) {
            return false;
        }

        return $view->getValue();
    }

    protected function getRootCategoryId()
    {
        if (empty($this->rootCategoryId)) {
            $this->rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();
        }

        return $this->rootCategoryId;
    }
}
