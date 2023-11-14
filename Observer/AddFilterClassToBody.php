<?php

namespace MageSuite\Frontend\Observer;

class AddFilterClassToBody implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\LayeredNavigation\Block\Navigation\State
     */
    protected $layeredState;

    /**
     * @var \MageSuite\Frontend\Service\BodyClassUpdater
     */
    protected $bodyClassUpdater;

    public function __construct(
        \Magento\LayeredNavigation\Block\Navigation\State $layeredState,
        \MageSuite\Frontend\Service\BodyClassUpdater $bodyClassUpdater
    )
    {
        $this->layeredState = $layeredState;
        $this->bodyClassUpdater = $bodyClassUpdater;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $response = $observer->getEvent()->getResponse();

        if (!$response) {
            return;
        }

        $html = $response->getBody();

        if (empty($html)) {
            return;
        }

        $activeFilters = $this->layeredState->getActiveFilters();
        $updatedHtml = $this->bodyClassUpdater->addFilterClassToBody($html, $activeFilters);

        if(!$updatedHtml){
            return;
        }

        $response->setBody($updatedHtml);

    }
}
