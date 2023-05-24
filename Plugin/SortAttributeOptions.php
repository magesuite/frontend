<?php

namespace MageSuite\Frontend\Plugin;

class SortAttributeOptions
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $resource;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    public function aroundGetAttributeOptions($subject, $proceed, $superAttribute, $productId)
    {
        $items = $proceed($superAttribute, $productId);

        if(empty($items)){
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

        if(count($sortOrder)){

            $sortedItems = [];
            foreach($items AS $item){
                if(!isset($sortOrder[$item['value_index']])) continue;
                $itemPosition = $sortOrder[$item['value_index']];
                $sortedItems[$itemPosition] = $item;
            }


            ksort($sortedItems);
            return $sortedItems;
        }

        return $items;
    }
}
