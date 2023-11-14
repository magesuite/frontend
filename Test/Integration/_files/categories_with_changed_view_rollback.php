<?php

/** @var \Magento\Framework\Registry $registry */
$registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var $product \Magento\Catalog\Model\Product */
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(435);
if ($category->getId()) {
    $category->delete();
}

/** @var $product \Magento\Catalog\Model\Product */
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(436);
if ($category->getId()) {
    $category->delete();
}

/** @var $product \Magento\Catalog\Model\Product */
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(437);
if ($category->getId()) {
    $category->delete();
}
