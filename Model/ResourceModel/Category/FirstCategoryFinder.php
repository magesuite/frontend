<?php

namespace MageSuite\Frontend\Model\ResourceModel\Category;

class FirstCategoryFinder
{
    protected \Magento\Framework\DB\Adapter\AdapterInterface $connection;

    public function __construct(\Magento\Framework\App\ResourceConnection $resourceConnection) {
        $this->connection = $resourceConnection->getConnection();
    }

    protected function getFirstCategoryForStore($categoryIds, $rootCategoryId)
    {
        $query = $this->connection->select();

        $query->from($this->connection->getTableName('catalog_category_entity'), 'entity_id');
        $query->where('entity_id IN(?)', $categoryIds);
        $query->where('path LIKE ?', '%/'.$rootCategoryId.'/%');
        $query->order('entity_id ASC');

        $ids = $this->connection->fetchCol($query);

        echo '';
    }
}
