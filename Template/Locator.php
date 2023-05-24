<?php

namespace MageSuite\Frontend\Template;

class Locator
{
    /**
     * @var \Magento\Framework\View\Element\Template\File\Resolver
     */
    private $resolver;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var \Magento\Framework\View\Design\Theme\Customization\Path
     */
    private $customization;

    /**
     * @var object
     */
    private $mainTheme;


    public function __construct(
        \Magento\Framework\View\Element\Template\File\Resolver $resolver,
        \Magento\Framework\App\State $state,
        \Magento\Framework\View\DesignInterface $design,
        \Magento\Framework\View\Design\Theme\Customization\Path $customization
    )
    {
        $this->resolver = $resolver;
        $this->state = $state;
        $this->customization = $customization;

        $theme = $design->getDesignTheme();

        if($theme == null) {
            return '';
        }

        $this->mainTheme = $theme;
    }

    public function locate($path)
    {
        if (!$this->isMagentoPath($path)) {
            $templatePath = $this->findPathInTheme($path, $this->mainTheme);

            if(!empty($templatePath)) {
                return $templatePath;
            }

            $this->throwTemplateNotFoundException($path);
        }

        $params = ['area' => $this->state->getAreaCode()];

        return $this->resolver->getTemplateFileName($path, $params);
    }

    protected function isMagentoPath($path)
    {
        return strpos($path, '::') !== false;
    }

    protected function findPathInTheme($path, $theme)
    {
        $templatePath = $this->returnPathFromTheme($path, $theme);

        if(!$templatePath AND $theme->getParentTheme()){
            $templatePath = $this->findPathInTheme($path, $theme->getParentTheme());
        }

        return $templatePath;
    }

    protected function returnPathFromTheme($path, $theme)
    {
        $basePath = $this->customization->getThemeFilesPath($theme);

        if ($this->pathBeginsWith($path, 'components/')) {
            $path = str_replace('components/', '', $path);
            return $this->returnPathFromComponents($path, $basePath);
        }

        $customizationsFilePath = $basePath . '/customizations/' . $path;

        if (file_exists($customizationsFilePath)) {
            return realpath($customizationsFilePath);
        }

        return $this->returnPathFromComponents($path, $basePath);
    }

    /**
     * @param $path
     */
    private function throwTemplateNotFoundException($path)
    {
        throw new \InvalidArgumentException('Template with path ' . $path . ' was not found');
    }

    /**
     * @param $path
     * @return bool
     */
    private function pathBeginsWith($path, $string)
    {
        return 0 === strpos($path, $string);
    }

    private function returnPathFromComponents($path, $basePath)
    {
        $componentsFilePath = $basePath . '/components/' . $path;

        if(file_exists($componentsFilePath)) {
            return realpath($componentsFilePath);
        }

        return false;
    }
}
