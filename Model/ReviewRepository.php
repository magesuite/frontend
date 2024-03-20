<?php

namespace MageSuite\Frontend\Model;

class ReviewRepository
{
    protected \Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $voteCollectionFactory;
    protected \Magento\Framework\DB\Adapter\AdapterInterface $connection;

    protected array $approvedReviewsIdsByEntity = [];

    public function __construct(
        \Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $voteCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->voteCollectionFactory = $voteCollectionFactory;
        $this->connection = $resourceConnection->getConnection();
    }

    public function getApprovedReviewsIdsByEntity(int $entityId, ?int $storeId = null): array
    {
        if (array_key_exists($entityId, $this->approvedReviewsIdsByEntity)) {
            return array_values($this->approvedReviewsIdsByEntity[$entityId]);
        }

        return $this->getApprovedReviewsIdsByEntities([$entityId], $storeId)[$entityId];
    }

    public function getApprovedReviewsIdsByEntities(array $entityIds, ?int $storeId = null): array
    {
        $select = $this->connection->select();
        $select->from(
            ['review' => $this->connection->getTableName('review')],
            ['entity_pk_value', 'review_id']
        );

        $select->join(
            ['store' => $this->connection->getTableName('review_store')],
            'review.review_id=store.review_id',
            []
        );
        $select->where('store.store_id = ?', $storeId);
        $select->where('review.status_id = ?', \Magento\Review\Model\Review::STATUS_APPROVED);

        $select->where('review.entity_pk_value IN (?)', $entityIds);

        $reviewEntityTable = $this->connection->getTableName('review_entity');
        $select->join(
            $reviewEntityTable,
            sprintf('review.entity_id=%s.entity_id', $reviewEntityTable),
            ['entity_code']
        );

        $select->where(sprintf('%s.entity_code = ?', $reviewEntityTable), 'product');

        $result = $this->connection->fetchAll($select);

        foreach($result as $reviewId) {
            $this->approvedReviewsIdsByEntity[$reviewId['entity_pk_value']][] = $reviewId['review_id'];
        }

        foreach($entityIds as $entityId) {
            if(isset($this->approvedReviewsIdsByEntity[$entityId])) {
                continue;
            }

            $this->approvedReviewsIdsByEntity[$entityId] = [];
        }

        return $this->approvedReviewsIdsByEntity;
    }
}
