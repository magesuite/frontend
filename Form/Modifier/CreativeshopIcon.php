<?php

namespace MageSuite\Frontend\Form\Modifier;

class CreativeshopIcon extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    const CREATIVESHOP_ICON_CSS_CLASS = 'cs-csfeature__logo';

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $attributesCodes = [
            'is_simplified_bundle'
        ];

        if(!is_array($meta) or empty($meta)) {
            return $meta;
        }

        foreach($attributesCodes as $attributeCode) {
            foreach($meta as &$fieldset) {
                if(!isset($fieldset["children"]["container_".$attributeCode]["children"][$attributeCode]["arguments"]["data"]["config"])) {
                    continue;
                }

                $fieldset["children"]["container_".$attributeCode]["children"][$attributeCode]["arguments"]["data"]["config"]['additionalClasses'] = self::CREATIVESHOP_ICON_CSS_CLASS;
            }
        }

        return $meta;
    }
}
