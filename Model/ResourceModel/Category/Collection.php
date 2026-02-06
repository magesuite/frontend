<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Model\ResourceModel\Category;

class Collection
{
    public function __construct(
        protected \Magento\Framework\App\ResourceConnection $resource,
        protected \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        protected \Magento\Eav\Model\Config $eavConfig
    ) {}

    public function getCategoriesCustomUrlAttributes(int $storeId): array
    {
        $linkField = $this->metadataPool->getMetadata(\Magento\Catalog\Api\Data\CategoryInterface::class)->getLinkField();
        $attribute = $this->eavConfig->getAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            \MageSuite\Frontend\Helper\Category::CATEGORY_CUSTOM_URL
        );
        $connection = $this->resource->getConnection();
        $select = $connection->select()
            ->from(['e' => $this->resource->getTableName('catalog_category_entity')], ['e.entity_id'])
            ->join(
                ['def_attr' => $attribute->getBackendTable()],
                implode(' AND ', [
                    sprintf('e.%1$s = def_attr.%1$s', $linkField),
                    'def_attr.store_id = 0',
                    $connection->quoteInto('def_attr.attribute_id = ?', $attribute->getId()),
                ]),
                [])
            ->joinLeft(
                ['attr' => $attribute->getBackendTable()],
                implode(' AND ', [
                    sprintf('e.%1$s = attr.%1$s', $linkField),
                    $connection->quoteInto('attr.store_id = ?', $storeId),
                    $connection->quoteInto('attr.attribute_id = ?', $attribute->getId()),
                ]),
                [])
            ->columns([
                'value' => $connection->getCheckSql(
                    'attr.value_id > 0',
                    $connection->quoteIdentifier('attr.value'),
                    $connection->quoteIdentifier('def_attr.value')
                )
            ])
            ->group("e.{$linkField}")
            ->having('value IS NOT NULL');

        return $connection->fetchPairs($select);
    }
}
