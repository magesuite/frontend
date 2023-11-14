<?php

namespace MageSuite\Frontend\Plugin;

class CategoryViewSwitcher
{
    /**
     * @var \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    private $layerResolver;

    /**
     * @var \Magento\Framework\App\Request\Http $request
     */
    private $request;

    public function __construct(
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\App\Request\Http $request
    )
    {
        $this->layerResolver = $layerResolver;
        $this->request = $request;
    }

    public function afterGetDefaultViewMode($subject, $result)
    {
        $category = $this->layerResolver->get()->getCurrentCategory();
        $view = $category->getCustomAttribute('category_view');
        $requestView = $this->request->getParam('product_list_mode');
        if ($view !== null) {
            return explode('-', $view->getValue())[0];
        }
        return $result;
    }
}
