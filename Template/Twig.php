<?php

namespace MageSuite\Frontend\Template;

class Twig
{
    /**
     * @var \Twig_Environment|null
     */
    private static $twigInstance = null;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \MageSuite\Frontend\Service\AssetLocator
     */
    private $assetLocator;

    /**
     * @var Locator
     */
    private $templateLocator;

    /**
     * @var Translator
     */
    private $templateTranslator;

    public function __construct(
        \MageSuite\Frontend\Service\AssetLocator $assetLocator,
        \MageSuite\Frontend\Template\Locator $templateLocator,
        \MageSuite\Frontend\Template\Translator $templateTranslator
    )
    {
        $this->twig = $this->getTwig();
        $this->assetLocator = $assetLocator;
        $this->templateLocator = $templateLocator;
        $this->templateTranslator = $templateTranslator;

        $function = new \Twig_SimpleFunction('asset', function ($assetLocation) {
            return $this->assetLocator->getUrl($assetLocation);
        });

        $this->twig->addFunction($function);

        $locateFunction = new \Twig_SimpleFunction('locate', function ($templatePath) {
            return $this->templateLocator->locate($templatePath);
        });

        $this->twig->addFunction($locateFunction);

        $translateFunction = new \Twig_SimpleFunction('translate', function (...$args) {
            return $this->templateTranslator->translate($args);
        });

        $this->twig->addFunction($translateFunction);
    }

    public function render(string $templateLocation, array $data)
    {
        return $this->twig->render($templateLocation, $data);
    }

    private function getTwig()
    {
        if(self::$twigInstance == null) {
            self::$twigInstance = new \Twig_Environment(
                new \Twig_Loader_Filesystem('/.'),
                ['cache' => BP.'/var/cache']
            );
        }

        return self::$twigInstance;
    }
}
