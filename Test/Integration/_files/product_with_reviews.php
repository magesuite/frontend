<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$product = $objectManager->create('Magento\Catalog\Model\Product');

$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(555)
    ->setAttributeSetId(4)
    ->setName('First product')
    ->setSku('first_product')
    ->setPrice(10)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setCanSaveCustomOptions(true)
    ->save();

$product = $objectManager->create('Magento\Catalog\Model\Product');

$product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
    ->setId(556)
    ->setAttributeSetId(4)
    ->setName('Second product')
    ->setSku('second_product')
    ->setPrice(10)
    ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
    ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
    ->setWebsiteIds([1])
    ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
    ->setCanSaveCustomOptions(true)
    ->save();


$storeId = 1;

$review = $objectManager->get('Magento\Review\Model\Review')
    ->setEntityPkValue(555)
    ->setStatusId(\Magento\Review\Model\Review::STATUS_APPROVED)
    ->setTitle('Test title')
    ->setDetail('Test details')
    ->setEntityId(1)
    ->setStoreId($storeId)
    ->setStores(1)
    ->setCustomerId(null)
    ->setNickname('Test')
    ->save();

$ratingOptions = array(
    '1' => '1',
    '2' => '2',
    '3' => '2',
    '4' => '3'
);

foreach ($ratingOptions AS $ratingId => $optionIds) {
    $objectManager->get('Magento\Review\Model\Rating')
        ->setRatingId($ratingId)
        ->setReviewId($review->getId())
        ->addOptionVote($optionIds, 555);

}

$review->aggregate();
