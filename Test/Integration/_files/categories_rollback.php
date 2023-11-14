<?php

/** @var \Magento\Framework\Registry $registry */
$registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var $category \Magento\Catalog\Model\Category */
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(333);
if ($category->getId()) {
    $category->delete();
}

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(334);
if ($category->getId()) {
    $category->delete();
}

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(335);
if ($category->getId()) {
    $category->delete();
}

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(336);
if ($category->getId()) {
    $category->delete();
}

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->load(337);
if ($category->getId()) {
    $category->delete();
}
