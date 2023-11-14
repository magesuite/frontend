<?php

/** @var \Magento\Catalog\Model\Category $category */
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(435);
if (!$category->getId()) {
    $category->isObjectNew(true);
    $category
        ->setId(435)
        ->setCreatedAt('2014-06-23 09:50:07')
        ->setName('Category with no view changed')
        ->setParentId(2)
        ->setPath('1/2/333/335')
        ->setLevel(4)
        ->setAvailableSortBy('name')
        ->setDefaultSortBy('name')
        ->setIsActive(true)
        ->setPosition(1)
        ->setAvailableSortBy(['position'])
        ->setFeaturedProducts('{"555":"","556":"","557":"","558":""}')
        ->setFeaturedProductsHeader('Featured Products Header')
        ->save();
}


$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(436);
if (!$category->getId()) {
    $category->isObjectNew(true);
    $category
        ->setId(436)
        ->setCreatedAt('2014-06-23 09:50:07')
        ->setName('Category with grid-list view')
        ->setParentId(2)
        ->setPath('1/2/333/336')
        ->setLevel(4)
        ->setAvailableSortBy('name')
        ->setDefaultSortBy('name')
        ->setIsActive(true)
        ->setPosition(2)
        ->setAvailableSortBy(['position'])
        ->setFeaturedProducts('{"555":"","556":"","557":"","558":""}')
        ->setFeaturedProductsHeader('Featured Products Header')
        ->setCustomAttribute('category_view', 'grid-list')
        ->save();
}
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(437);
if (!$category->getId()) {
    $category->isObjectNew(true);
    $category
        ->setId(437)
        ->setCreatedAt('2014-06-23 09:50:07')
        ->setName('Category changing view')
        ->setParentId(2)
        ->setPath('1/2/333/337')
        ->setLevel(4)
        ->setAvailableSortBy('name')
        ->setDefaultSortBy('name')
        ->setIsActive(true)
        ->setPosition(3)
        ->setAvailableSortBy(['position'])
        ->setFeaturedProducts('{"555":"","556":"","557":"","558":""}')
        ->setFeaturedProductsHeader('Featured Products Header')
        ->setCustomAttribute('category_view', 'list-grid')
        ->save();
}
/** @var \Magento\Store\Model\Store $storeModel */
$storeModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Store\Model\Store');
$defaultStore = $storeModel
    ->load('default');

/** @var \Magento\Catalog\Model\Category $categoryModel */
$categoryModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Catalog\Model\Category');
$category = $categoryModel
    ->setStoreId($defaultStore->getId())
    ->load(437);
$category
    ->setCustomAttribute('category_view', 'list')
    ->save();

/** @var \Magento\Store\Model\Store $storeModel */
$storeModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Store\Model\Store');
$adminStore = $storeModel
    ->load('admin');

/** @var \Magento\Catalog\Model\Category $categoryModel */
$categoryModel = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Catalog\Model\Category');
$category = $categoryModel
    ->setStoreId($adminStore->getId())
    ->load(437);
$category
    ->setCustomAttribute('category_view', 'grid')
    ->save();
