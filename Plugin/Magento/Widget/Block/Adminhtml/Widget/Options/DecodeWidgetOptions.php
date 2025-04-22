<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Magento\Widget\Block\Adminhtml\Widget\Options;

class DecodeWidgetOptions
{
    public function __construct(
        protected \MageSuite\Frontend\Model\Config\EncodedWidgetParamsConfig $paramsToDecodeConfig
    ) {
    }

    public function beforeAddFields(\Magento\Widget\Block\Adminhtml\Widget\Options $subject): void
    {
        $paramsToDecode = $this->paramsToDecodeConfig->getParams();

        if (empty($paramsToDecode)) {
            return;
        }

        $widgetValues = $subject->getWidgetValues();
        $dataHasChanged = false;

        foreach ($paramsToDecode as $key) {
            if (!isset($widgetValues[$key])) {
                continue;
            }

            $param = $widgetValues[$key];

            if (is_string($param) && $this->paramsToDecodeConfig->isBase64Encoded($param)) {
                $widgetValues[$key] =  base64_decode((string)$param);
                $dataHasChanged = true;
            }
        }

        if ($dataHasChanged) {
            $subject->setWidgetValues($widgetValues);
        }
    }
}
