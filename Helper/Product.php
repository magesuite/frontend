<?php

namespace MageSuite\Frontend\Helper;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SPECIAL_PRICE = 'special';
    const REGULAR_PRICE = 'regular';
    const MAGENTO_ENTERPRISE = 'Enterprise';

    /**
     * @var \MageSuite\Frontend\Helper\Review
     */
    protected $reviewHelper;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $magentoProductMetadata;

    /**
     * @var \MageSuite\Discount\Helper\Discount
     */
    protected $discountHelper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \MageSuite\Frontend\Helper\Review $reviewHelper,
        \Magento\Framework\App\ProductMetadataInterface $magentoProductMetadata,
        \MageSuite\Discount\Helper\Discount $discountHelper
    ) {
        parent::__construct($context);
        $this->reviewHelper = $reviewHelper;
        $this->magentoProductMetadata = $magentoProductMetadata;
        $this->discountHelper = $discountHelper;
    }

    public function getReviewSummary($product, $includeVotes = false)
    {
        return $this->reviewHelper->getReviewSummary($product, $includeVotes);
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param string|null $date
     * @return bool
     */
    public function isNew($product, $date = null)
    {
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate = $product->getNewsToDate();

        if($newsFromDate === null && $newsToDate === null) {
            return false;
        }

        $date = $date ?: date('Y-m-d');

        $fromTimestamp = ($newsFromDate === null) ? null : strtotime($newsFromDate);
        $toTimestamp = ($newsToDate === null) ? null : strtotime($newsToDate);
        $dateTimestamp = strtotime($date);

        if (!$fromTimestamp && !$toTimestamp) {
            return false;
        }

        if (!$fromTimestamp && $dateTimestamp <= $toTimestamp) {
            return true;
        }

        if (!$toTimestamp && $dateTimestamp >= $fromTimestamp) {
            return true;
        }

        if ($dateTimestamp >= $fromTimestamp && $dateTimestamp <= $toTimestamp) {
            return true;
        }

        return false;
    }

    public function getAddToCartUrl($productId)
    {
        $routeParams = [
            'product' => $productId,
            '_secure' => $this->_getRequest()->isSecure()
        ];

        return $this->_getUrl('checkout/cart/add', $routeParams);
    }

    public function isMagentoEnterprise()
    {
        return $this->magentoProductMetadata->getEdition() == self::MAGENTO_ENTERPRISE;
    }

    /**
     * @deprecated
     */
    public function isOnSale($product, $finalPrice = null)
    {
        return $this->discountHelper->isOnSale($product, $finalPrice);
    }

    /**
     * @deprecated
     */
    public function checkIsProductOnSale($product, $finalPrice = null)
    {

        if (empty($finalPrice)) {
            $finalPrice = $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE)->getAmount()->getValue();
        }

        $productPrice = $product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\RegularPrice::PRICE_CODE)->getAmount()->getValue();

        if (empty($productPrice)) {
            return false;
        }

        if ($productPrice > $finalPrice) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated
     */
    public function getSalePercentage($product, $finalPrice = null)
    {
        return $this->discountHelper->getSalePercentage($product, $finalPrice);
    }

    /**
     * @deprecated
     */
    public function getConfigurableDiscounts($product)
    {
        return $this->discountHelper->getConfigurableDiscounts($product);
    }
}
