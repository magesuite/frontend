<?php

namespace MageSuite\Frontend\Block\Navigation;

class ActiveCategory extends \Magento\Framework\View\Element\Template
{
    protected $registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->registry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * Returns current visited category id
     * If we're not on category page then we return 0
     * @return int
     */
    public function getActiveCategoryId()
    {
        /** @var \Magento\Catalog\Model\Category $currentCategory */
        $currentCategory = $this->registry->registry('current_category');

        if($currentCategory == null) {
            return 0;
        }

        return $currentCategory->getId();
    }
}
