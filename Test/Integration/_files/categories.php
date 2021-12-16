<?php

/** @var \Magento\Catalog\Model\Category $category */
$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(333)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Main category')
    ->setParentId(2)
    ->setPath('1/2/333')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setImageTeaserDescription('Image Teaser Description')
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(334)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('First subcategory')
    ->setParentId(333)
    ->setPath('1/2/333/334')
    ->setLevel(4)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(335)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Second subcategory')
    ->setParentId(333)
    ->setPath('1/2/333/335')
    ->setLevel(4)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setIncludeInMenu(0)
    ->setCategoryIcon('icon.png')
    ->setImageTeaser('teaser.png')
    ->setImageTeaserSlogan('Image Teaser Slogan')
    ->setImageTeaserDescription('Image Teaser Description')
    ->setImageTeaserCtaLabel('Image Teaser CTA Label')
    ->setImageTeaserCtaLink('url')
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(336)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Third subcategory')
    ->setParentId(333)
    ->setPath('1/2/333/336')
    ->setLevel(4)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setIncludeInMenu(0)
    ->save()
    ->reindex();


$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(337)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Subcategory of third subcategory')
    ->setParentId(336)
    ->setPath('1/2/333/336/337')
    ->setLevel(5)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(338)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Category with custom url')
    ->setParentId(2)
    ->setPath('1/2/338')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setCategoryCustomUrl('contact')
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(339)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Category with custom url to cms page')
    ->setParentId(338)
    ->setPath('1/2/338/339')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setCategoryCustomUrl('{{widget type="Magento\Cms\Block\Widget\Page\Link" anchor_text="CUSTOM_TEXT" title="CUSTOM_TITLE" template="widget/link/link_inline.phtml" page_id="100"}}')
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(340)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Category with custom url to product')
    ->setParentId(339)
    ->setPath('1/2/338/339/340')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setCategoryCustomUrl('{{widget type="Magento\Catalog\Block\Product\Widget\Link" anchor_text="CUSTOM_TEXT" title="CUSTOM_TITLE" template="widget/link/link_inline.phtml" id_path="product/881"}}')
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(341)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Category with custom url to category')
    ->setParentId(340)
    ->setPath('1/2/338/339/340/341')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setCategoryCustomUrl('{{widget type="Magento\Catalog\Block\Category\Widget\Link" anchor_text="CUSTOM_TEXT" title="CUSTOM_TITLE" template="widget/link/link_inline.phtml" id_path="category/338"}}')
    ->save()
    ->reindex();

$category = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Category');
$category->isObjectNew(true);
$category
    ->setId(342)
    ->setCreatedAt('2014-06-23 09:50:07')
    ->setName('Category with broken directive')
    ->setParentId(338)
    ->setPath('1/2/338/342')
    ->setLevel(3)
    ->setAvailableSortBy('name')
    ->setDefaultSortBy('name')
    ->setIsActive(true)
    ->setPosition(1)
    ->setAvailableSortBy(['position'])
    ->setCategoryCustomUrl('{{widget type="Magento\Catalog\Block\Category\Widget\Link" anchor_text="CUSTOM_TEXT" title="CUSTOM_TITLE" template="widget/link/link_inline.phtml" id_path="category/123421"}}')
    ->save()
    ->reindex();
