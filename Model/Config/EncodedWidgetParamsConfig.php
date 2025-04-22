<?php

declare(strict_types=1);

namespace MageSuite\Frontend\Model\Config;

class EncodedWidgetParamsConfig
{
    public function __construct(
        protected array $paramsToEncode = []
    ) {
    }

    public function getParams(): array
    {
        return $this->paramsToEncode;
    }

    public function isBase64Encoded(string $string): bool
    {
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) {
            return false;
        }

        $decoded = base64_decode($string, true);

        if ($decoded === false || base64_encode($decoded) !== str_replace(["\r", "\n"], '', $string)) {
            return false;
        }

        return true;
    }
}
