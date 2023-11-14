<?php

namespace MageSuite\Frontend\Service;

class BodyClassUpdater
{
    public function addFilterClassToBody($html, $filters)
    {
        if(!$html){
            return null;
        }

        if(empty($filters)){
            return null;
        }

        if(strpos($html, '<body') === false) {
            return null;
        }

        if(strpos($html, 'page-with-filter') === false) {
            return null;
        }

        return str_replace('page-with-filter', 'page-with-filter page-with-active-filter', $html);
    }
}
