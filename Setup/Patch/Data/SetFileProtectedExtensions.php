<?php

namespace MageSuite\Frontend\Setup\Patch\Data;

/** Patch copied data from config.xml file to the database in order to remove svg extension from file/protected_extensions setting */
class SetFileProtectedExtensions implements \Magento\Framework\Setup\Patch\DataPatchInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $configWriter;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
    }

    public function apply()
    {
        $fileProtectedExtensions = $this->getFileProtectedExtensions();

        $this->configWriter->save(
            \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension::XML_PATH_PROTECTED_FILE_EXTENSIONS,
            implode(',', $fileProtectedExtensions)
        );
    }

    protected function getFileProtectedExtensions()
    {
        $fileProtectedExtensions = explode(
            ',',
            $this->scopeConfig->getValue(\Magento\MediaStorage\Model\File\Validator\NotProtectedExtension::XML_PATH_PROTECTED_FILE_EXTENSIONS)
        );

        $svgPosition = array_search('svg', $fileProtectedExtensions);

        if ($svgPosition !== false) {
            unset($fileProtectedExtensions[$svgPosition]);
        }

        return $fileProtectedExtensions;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
