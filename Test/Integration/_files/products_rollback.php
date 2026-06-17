<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

$registry = $objectManager->get('Magento\Framework\Registry');
$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

$categoryId = 333;
$productIds = [881, 882, 883];

$category = $objectManager->create('Magento\Catalog\Model\Category');

$category->load($categoryId);
if ($category->getId()) {
    $category->delete();
}

foreach ($productIds as $productId) {
    $product = $objectManager->create('Magento\Catalog\Model\Product');

    $product->load($productId);
    if ($product->getId()) {
        $product->delete();
    }
}

// The DB is rolled back by @magentoDbIsolation but Elasticsearch is not. Reindex the now-deleted
// products so the fulltext indexer removes them from the shared index instead of leaking into
// other tests' category queries.
$objectManager->get(\Magento\CatalogSearch\Model\Indexer\Fulltext\Processor::class)->reindexList($productIds, false);
