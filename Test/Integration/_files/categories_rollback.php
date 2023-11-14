<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$categoryIds = [333,334,335,336,337,338];

foreach($categoryIds as $categoryId){
    $category = $objectManager->create('Magento\Catalog\Model\Category')->load($categoryId);

    if ($category->getId()) {
        $category->delete();
    }
}
