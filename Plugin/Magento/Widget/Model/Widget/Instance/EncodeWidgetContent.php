<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Plugin\Magento\Widget\Model\Widget\Instance;

class EncodeWidgetContent
{
    public function __construct(
        protected \MageSuite\Frontend\Model\Config\EncodedWidgetParamsConfig $paramsToEncodeConfig
    ) {
    }

    public function beforeSave(\Magento\Widget\Model\Widget\Instance $subject): void
    {
        $params = $subject->getWidgetParameters();
        $paramsToEncode = $this->paramsToEncodeConfig->getParams();

        if (empty($paramsToEncode)) {
            return;
        }

        $paramsEncoded = false;

        foreach ($paramsToEncode as $key) {
            if (!isset($params[$key])) {
                continue;
            }

            if (is_string($params[$key])) {
                $params[$key] = base64_encode($params[$key]);
                $paramsEncoded = true;
            }
        }

        if ($paramsEncoded) {
            $subject->setWidgetParameters($params);
        }
    }
}
