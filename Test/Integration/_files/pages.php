<?php
/** @var $store \Magento\Store\Model\Store */
$store = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Store\Model\Store');
if (!$store->load('second', 'code')->getId()) {
    $websiteId = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        'Magento\Store\Model\StoreManagerInterface'
    )->getWebsite()->getId();
    $groupId = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
        'Magento\Store\Model\StoreManagerInterface'
    )->getWebsite()->getDefaultGroupId();
    $store->setCode(
        'second'
    )->setWebsiteId(
        $websiteId
    )->setGroupId(
        $groupId
    )->setName(
        'Second Store View'
    )->setSortOrder(
        10
    )->setIsActive(
        1
    );
    $store->save();
}

/* Refresh stores memory cache */
\Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get(
    'Magento\Store\Model\StoreManagerInterface'
)->reinitStores();

/** @var $page \Magento\Cms\Model\Page */
$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Cms\Model\Page');
$page->setTitle('Cms Page 1 Default Store View')
    ->setId(100)
    ->setIdentifier('site1-default')
    ->setStores([1])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 1 Default Store View</h1>')
    ->setPageLayout('1column')
    ->setPageGroupIdentifier('site1')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Cms\Model\Page');
$page->setTitle('Cms Page 1 Second Store View')
    ->setId(101)
    ->setIdentifier('site1-second')
    ->setStores([$store->getId()])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 1 Second Store View</h1>')
    ->setPageLayout('1column')
    ->setPageGroupIdentifier('site1')
    ->save();

$page->setTitle('Cms Page 2 Default Store View')
    ->setId(102)
    ->setIdentifier('site2-default')
    ->setStores([1])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 2 Default Store View</h1>')
    ->setPageLayout('1column')
    ->setPageGroupIdentifier('site2')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Cms\Model\Page');
$page->setTitle('Cms Page 2 Second Store View')
    ->setId(103)
    ->setIdentifier('site2-second')
    ->setStores([$store->getId()])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 2 Second Store View</h1>')
    ->setPageLayout('1column')
    ->setPageGroupIdentifier('')
    ->save();

$page->setTitle('Cms Page 3 Default Store View')
    ->setId(104)
    ->setIdentifier('m2c')
    ->setStores([1])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 3 Default Store View</h1>')
    ->setPageLayout('1column')
    ->setPageGroupIdentifier('site3')
    ->save();

$page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Cms\Model\Page');
$page->setTitle('Cms Page 3 Second Store View')
    ->setId(105)
    ->setIdentifier('m2c-site-3')
    ->setStores([$store->getId()])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page 3 Second Store View</h1>')
    ->setPageLayout('1column')
    ->setPageGroupIdentifier('site3')
    ->save();
