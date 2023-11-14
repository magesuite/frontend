<?php

namespace MageSuite\Frontend\Block;

class Twig extends \Magento\Framework\View\Element\Template implements \Magento\Framework\View\Element\BlockInterface
{
    /**
     * @var \MageSuite\Frontend\Template\Twig
     */
    private $twig;
    /**
     * @var \MageSuite\Frontend\Template\Locator
     */
    private $locator;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MageSuite\Frontend\Template\Twig $twig,
        \MageSuite\Frontend\Template\Locator $locator,
        array $data = []
    )
    {
        parent::__construct($context, $data);

        $this->twig = $twig;
        $this->locator = $locator;
    }

    /**
     * Produce and return block's html output
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->twig->render(
            $this->locator->locate($this->getTemplate()),
            $this->_data
        );
    }
}
