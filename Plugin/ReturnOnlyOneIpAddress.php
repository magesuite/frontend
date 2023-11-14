<?php

namespace MageSuite\Frontend\Plugin;

class ReturnOnlyOneIpAddress
{
    public function afterGetRemoteAddress(\Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $subject, $result) {
        if(strpos($result, ',') === false) {
            return $result;
        }

        $ipAddresses = explode(',', $result);

        return trim($ipAddresses[0]);
    }
}
