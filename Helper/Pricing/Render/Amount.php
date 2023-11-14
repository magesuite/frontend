<?php

namespace MageSuite\Frontend\Helper\Pricing\Render;

class Amount extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    private $priceCurrency;

    /**
     * Amount constructor.
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

    public function formatCurrencyForSeo(
        $price,
        $includeContainer = true,
        $precision = \Magento\Framework\Pricing\PriceCurrencyInterface::DEFAULT_PRECISION
    )
    {
        $price = $this->priceCurrency->format($price, $includeContainer, $precision);
        // substr was used because of currency sign at the end of string
        return substr($price, 0, -5);
    }
}
