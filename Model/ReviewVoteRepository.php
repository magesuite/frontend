<?php

namespace MageSuite\Frontend\Model;

class ReviewVoteRepository
{
    protected \Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $voteCollectionFactory;
    protected array $votesByEntity = [];

    public function __construct(\Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $voteCollectionFactory)
    {
        $this->voteCollectionFactory = $voteCollectionFactory;
    }

    public function getVotesByEntity(int $entityId, ?int $storeId = null): array
    {
        if (array_key_exists($entityId, $this->votesByEntity)) {
            return array_values($this->votesByEntity[$entityId]);
        }

        return $this->getVotesByEntities([$entityId], $storeId);
    }

    public function getVotesByEntities(array $entityIds, ?int $storeId = null): array
    {
        $votes = $this->voteCollectionFactory->create();

        if ($storeId !== null) {
            $votes->setStoreFilter($storeId);
        }

        $votes->getSelect()->where('entity_pk_value IN (?)', $entityIds);

        $result = [];

        foreach ($votes->getItems() as $vote) {
            $this->votesByEntity[$vote->getEntityPkValue()][$vote->getId()] = $vote;
            $result[] = $vote;
        }

        return $result;
    }
}
