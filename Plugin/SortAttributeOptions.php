<?php
declare(strict_types=1);

namespace MageSuite\Frontend\Plugin;

class SortAttributeOptions
{
    protected \Magento\Framework\App\ResourceConnection $resource;

    public function __construct(\Magento\Framework\App\ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    public function aroundGetAttributeOptions(\Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $subject, $proceed, $superAttribute, $productId)
    {
        $items = $proceed($superAttribute, $productId);

        if (empty($items)) {
            return $items;
        }

        $connection = $this->resource->getConnection();
        $select = $connection->select()->from(
            ['attribute_opt' => $this->resource->getTableName('eav_attribute_option')],
            ['option_id', 'sort_order']
        )->where(
            'attribute_opt.attribute_id = ?',
            $superAttribute->getAttributeId()
        )->order(
            'attribute_opt.sort_order ASC'
        );
        $sortOrder = array_flip(array_keys($connection->fetchPairs($select)));

        if (empty($sortOrder)) {
            return $items;
        }

        uasort($items, function ($firstElement, $secondElement) use ($sortOrder) {
            return (int)$sortOrder[$firstElement['value_index'] ?? 0] <=> (int)$sortOrder[$secondElement['value_index'] ?? 0];
        });

        return $items;
    }
}
