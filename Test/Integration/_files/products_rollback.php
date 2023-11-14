<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get('Magento\Framework\Registry');
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$categoryId = 333;

$category = $objectManager->create('Magento\Catalog\Model\Category');

$category->load($categoryId);
if ($category->getId()) {
    $category->delete();
}

foreach ([881, 882, 883] as $productId) {
    $product = $objectManager->create('Magento\Catalog\Model\Product');

    $product->load($productId);
    if ($product->getId()) {
        $product->delete();
    }
}
