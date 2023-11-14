<?php

namespace MageSuite\Frontend\Service\Image;

interface Optimizer
{
    /**
     * Optimizes image
     * @param $filePath
     * @return mixed
     */
    public function optimize($filePath);
}
