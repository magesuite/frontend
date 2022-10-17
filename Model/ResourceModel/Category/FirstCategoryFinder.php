<?php

namespace MageSuite\Frontend\Model\ResourceModel\Category;

class FirstCategoryFinder
{
    protected \Magento\Framework\DB\Adapter\AdapterInterface $connection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection)
    {
        $this->connection = $resourceConnection->getConnection();
    }

    public function getFirstCategoryIdForStore($categoryIds, $rootCategoryId)
    {
        $select = $this->connection->select();

        $select->from($this->connection->getTableName('catalog_category_entity'), 'entity_id');
        $select->where('entity_id IN(?)', $categoryIds);
        $select->where('path LIKE ?', "%/$rootCategoryId/%");
        $select->order('entity_id ASC');
        $select->limit(1);

        return $this->connection->fetchOne($select);
    }
}
