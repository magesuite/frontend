<?php
namespace MageSuite\Frontend\Block\Product\View;

class ProductCategory extends \Magento\Framework\View\Element\Template
{
    protected $_registry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_registry = $registry;
        parent::__construct($context, $data);
    }


    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

}
?>
