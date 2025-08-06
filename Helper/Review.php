<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Helper;

class Review
{
    public const MAX_STARS_VALUE = 5;

    /**
     * @var \Magento\Review\Model\Rating[]
     */
    protected array $ratings;

    public function __construct(
        protected \Magento\Store\Model\StoreManagerInterface $storeManager,
        protected \Magento\Review\Model\ResourceModel\Rating\CollectionFactory $ratingCollectionFactory,
        protected \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        protected \MageSuite\Frontend\Model\ReviewVoteRepository $reviewVoteRepository,
        protected \MageSuite\Frontend\Model\ReviewRepository $reviewRepository,
        protected \Magento\Review\Model\AppendSummaryData $appendSummaryData,
    ) {}

    public function getReviewSummary(\Magento\Catalog\Model\Product $product, bool $includeVotes = false): array
    {
        $reviewData = [
            'data' => [
                'maxStars' => $this->getMaxStarsValue(),
                'activeStars' => 0,
                'count' => 0,
                'votes' => array_fill(1, $this->getMaxStarsValue(), 0),
                'ratings' => [],
            ],
        ];

        $storeId = (int)$this->storeManager->getStore()->getId();

        $ratingSummary = $product->getRatingSummary();

        if (!$ratingSummary) {
            $this->appendReviewSummary($product, $storeId);
            $ratingSummary = $product->getRatingSummary();
        }

        $reviewsCount = $product->getReviewsCount();

        if (is_object($ratingSummary)) {
            $reviewsCount = $ratingSummary->getReviewsCount();
            $ratingSummary = $ratingSummary->getRatingSummary();
        }

        if ($ratingSummary) {
            $reviewData['data']['activeStars'] = $this->getStarsAmount($ratingSummary);
            $reviewData['data']['count'] = $reviewsCount;

            if ($includeVotes && $reviewData['data']['count']) {
                $reviewData = $this->prepareAdditionalRatingData($reviewData, (int)$product->getId(), $storeId);
            }
        }

        return $reviewData;
    }

    public function appendReviewSummary(\Magento\Catalog\Model\Product $product, int $storeId): void
    {
        $this->appendSummaryData->execute($product, $storeId, \Magento\Review\Model\Review::ENTITY_PRODUCT_CODE);
    }

    public function prepareAdditionalRatingData(array $reviewData, int $productId, int $storeId): array
    {
        $votes = $this->getVotes($productId, $storeId);

        $groupedVotes = [
            'review' => [],
            'rating' => [],
        ];

        foreach ($votes as $vote) {
            $vote->getData();
            $groupedVotes['review'][$vote->getReviewId()][] = $vote->getPercent();
            $groupedVotes['rating'][$vote->getRatingId()][] = $vote->getPercent();
        }

        $ratings = $this->getRatings();
        $approvedReviews = $this->getApprovedReviews($productId, $storeId);

        foreach ($groupedVotes as $type => $group) {
            foreach ($group as $typeId => $votes) {
                $averageRating = $this->getAverageRating($votes);

                if ($type == 'review' && in_array($typeId, $approvedReviews)) {
                    $reviewData['data']['votes'][$this->getRoundReviewStarsAmount($averageRating)]++;
                } elseif ($type == 'rating') {
                    $reviewData['data']['ratings'][$typeId]['starsAmount'] = $this->getStarsAmount($averageRating);
                    $reviewData['data']['ratings'][$typeId]['label'] = isset($ratings[$typeId]) ? $ratings[$typeId]->getRatingCode() : null;
                }
            }
        }

        return $reviewData;
    }

    public function getVotes(int $productId, int $storeId): array
    {
        return $this->reviewVoteRepository->getVotesByEntity($productId, $storeId);
    }

    /**
     * @return \Magento\Review\Model\Rating[]
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRatings(): array
    {
        if (!isset($this->ratings)) {
            $storeId = $this->storeManager->getStore()->getId();

            $ratings = $this->ratingCollectionFactory->create()
                ->addEntityFilter('product')
                ->setPositionOrder()
                ->setStoreFilter($storeId)
                ->addRatingPerStoreName($storeId);

            $this->ratings = $ratings->getItems();
        }

        return $this->ratings;
    }

    public function getApprovedReviews(int $productId, int $storeId): array
    {
        return $this->reviewRepository->getApprovedReviewsIdsByEntity($productId, $storeId);
    }

    public function getMaxStarsValue(): int
    {
        return self::MAX_STARS_VALUE;
    }

    protected function getAverageRating(array $votes): float
    {
        return array_sum($votes) / count($votes);
    }

    protected function getStarsAmount(array|float|string $value): string
    {
        if (is_array($value)) {
            $value = array_sum($value) / count($value);
        }

        return number_format($value / 10 / 2, 2);
    }

    protected function getRoundReviewStarsAmount(float $rating): int
    {
        return (int)round($rating / 20);
    }
}
