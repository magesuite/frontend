<?php

namespace MageSuite\Frontend\Helper;

class Review extends \Magento\Framework\App\Helper\AbstractHelper
{
    const MAX_STARS_VALUE = 5;

    protected \Magento\Review\Model\Review $review;
    protected \Magento\Review\Model\ResourceModel\Rating\Option\Vote\CollectionFactory $voteCollectionFactory;
    protected \Magento\Store\Model\StoreManagerInterface $storeManager;
    protected \Magento\Review\Model\ResourceModel\Rating\CollectionFactory $ratingCollectionFactory;
    protected \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory;
    protected \MageSuite\Frontend\Model\ReviewVoteRepository $reviewVoteRepository;
    protected \MageSuite\Frontend\Model\ReviewRepository $reviewRepository;

    /**
     * @var \Magento\Review\Model\Rating[]
     */
    protected ?array $ratings = null;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Review\Model\Review $review,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Review\Model\ResourceModel\Rating\CollectionFactory $ratingCollectionFactory,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $reviewCollectionFactory,
        \MageSuite\Frontend\Model\ReviewVoteRepository $reviewVoteRepository,
        \MageSuite\Frontend\Model\ReviewRepository $reviewRepository
    ) {
        parent::__construct($context);

        $this->review = $review;
        $this->storeManager = $storeManager;
        $this->ratingCollectionFactory = $ratingCollectionFactory;
        $this->reviewCollectionFactory = $reviewCollectionFactory;
        $this->reviewVoteRepository = $reviewVoteRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function getReviewSummary($product, $includeVotes = false)
    {
        $reviewData = [
            'data' => [
                'maxStars' => self::MAX_STARS_VALUE,
                'activeStars' => 0,
                'count' => 0,
                'votes' => array_fill(1, self::MAX_STARS_VALUE, 0),
                'ratings' => []
            ]
        ];

        if ($product) {
            $storeId = $this->storeManager->getStore()->getId();
            $ratingSummary = $product->getRatingSummary();
            $reviewsCount = $product->getReviewsCount();

            if (!$ratingSummary) {
                $this->review->getEntitySummary($product, $storeId);
                $ratingSummary = $product->getRatingSummary();
            }
            // Since 2.3.3 rating summary is being returned directly, not as an object.
            if (is_object($ratingSummary)) {
                $reviewsCount = $ratingSummary->getReviewsCount();
                $ratingSummary = $ratingSummary->getRatingSummary();
            }

            if ($ratingSummary) {
                $reviewData['data']['activeStars'] = $ratingSummary ? $this->getStarsAmount($ratingSummary) : 0;
                $reviewData['data']['count'] = $reviewsCount;

                if ($includeVotes && $reviewData['data']['count']) {
                    $reviewData = $this->prepareAdditionalRatingData($reviewData, $product->getId(), $storeId);
                }
            }
        }

        return $reviewData;
    }

    protected function prepareAdditionalRatingData($reviewData, $productId, $storeId)
    {
        $votes = $this->reviewVoteRepository->getVotesByEntity($productId, $storeId);

        $groupedVotes = [
            'review' => [],
            'rating' => []
        ];

        foreach ($votes as $vote) {
            $vote->getData();
            $groupedVotes['review'][$vote->getReviewId()][] = $vote->getPercent();
            $groupedVotes['rating'][$vote->getRatingId()][] = $vote->getPercent();
        }

        $ratings = $this->getRatings();
        $approvedReviews = $this->reviewRepository->getApprovedReviewsIdsByEntity($productId, $storeId);

        foreach ($groupedVotes as $type => $group) {
            foreach ($group as $typeId => $votes) {
                $starsAmount = $this->getStarsAmount($votes);

                if ($type == 'review' && in_array($typeId, $approvedReviews)) {
                    $reviewData['data']['votes'][$this->roundReviewStarsAmount($starsAmount)]++;
                } elseif ($type == 'rating') {
                    $reviewData['data']['ratings'][$typeId]['starsAmount'] = $starsAmount;
                    $reviewData['data']['ratings'][$typeId]['label'] = isset($ratings[$typeId]) ? $ratings[$typeId]->getRatingCode() : null;
                }
            }
        }

        return $reviewData;
    }

    protected function getStarsAmount($value)
    {
        if (is_array($value)) {
            $value = array_sum($value) / count($value);
        }

        return round($value / 10) / 2;
    }

    protected function roundReviewStarsAmount($startsAmount)
    {
        return round($startsAmount);
    }

    /**
     * @return \Magento\Review\Model\Rating[]|null
     */
    public function getRatings()
    {
        if ($this->ratings == null) {
            $storeId = $this->storeManager->getStore()->getId();

            $ratings = $this->ratingCollectionFactory->create()
                ->addEntityFilter('product')
                ->setPositionOrder()
                ->setStoreFilter($storeId)
                ->addRatingPerStoreName($storeId)
                ->load();

            /** @var \Magento\Review\Model\Rating $rating */
            foreach ($ratings as $rating) {
                $this->ratings[$rating->getId()] = $rating;
            }
        }

        return $this->ratings;
    }
}
