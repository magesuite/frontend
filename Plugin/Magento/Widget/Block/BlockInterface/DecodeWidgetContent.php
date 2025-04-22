<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Magento\Widget\Block\BlockInterface;

class DecodeWidgetContent
{
    public function __construct(
        protected \MageSuite\Frontend\Model\Config\EncodedWidgetParamsConfig $paramsToDecodeConfig
    ) {
    }

    public function beforeToHtml(\Magento\Widget\Block\BlockInterface $subject): void
    {
        $params = $subject->getData();
        $paramsToDecode = $this->paramsToDecodeConfig->getParams();

        foreach ($paramsToDecode as $key) {
            if (!isset($params[$key])) {
                continue;
            }

            $param = $params[$key];

            if (is_string($param) && $this->paramsToDecodeConfig->isBase64Encoded($param)) {
                $subject->setData($key, base64_decode((string)$param));
            }
        }
    }
}
