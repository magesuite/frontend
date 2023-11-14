<?php

namespace MageSuite\Frontend\Helper\Product;

class Stock extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\CatalogInventory\Api\StockStatusCriteriaInterfaceFactory
     */
    private $stockStatusCriteriaFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStatusRepositoryInterface
     */
    private $stockStatusRepository;

    /**
     * @var \Magento\CatalogInventory\Api\Data\StockStatusInterfaceFactory
     */
    private $stockStatusFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\CatalogInventory\Api\StockStatusCriteriaInterfaceFactory $stockStatusCriteriaFactory,
        \Magento\CatalogInventory\Api\StockStatusRepositoryInterface $stockStatusRepository,
        \Magento\CatalogInventory\Api\Data\StockStatusInterfaceFactory $stockStatusFactory
    )
    {
        parent::__construct($context);

        $this->stockStatusCriteriaFactory = $stockStatusCriteriaFactory;
        $this->stockStatusRepository = $stockStatusRepository;
        $this->stockStatusFactory = $stockStatusFactory;
    }

    public function getStockStatuses($productIds)
    {
        $criteria = $this->stockStatusCriteriaFactory->create();
        $criteria->setProductsFilter([$productIds]);

        $collection = $this->stockStatusRepository->getList($criteria);

        $stockStatuses = [];

        foreach($productIds as $productId) {
            $stockStatuses[$productId] = null;
        }

        foreach($collection->getItems() as $stockStatus) {
            $stockStatuses[$stockStatus->getProductId()] = $stockStatus;
        }

        foreach($stockStatuses as $productId => $stockStatus) {
            if($stockStatus == null) {
                $stockStatuses[$productId] = $this->stockStatusFactory->create();
            }
        }

        return $stockStatuses;
    }
}
