<?php
/**
 * Created by PhpStorm.
 * User: krzysztofmoskalik
 * Date: 16.01.2018
 * Time: 14:35
 */

namespace MageSuite\Frontend\Plugin;


class PreserveTransparencyOnModal
{
    /**
     * @var \Magento\Framework\Url\DecoderInterface
     */
    protected $urlDecoder;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Cms\Model\Template\Filter $filter
     */
    protected $filter;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $adapter;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * DirectivePlugin constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\Url\DecoderInterface $urlDecoder
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Url\DecoderInterface $urlDecoder,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Cms\Model\Template\Filter $filter,
        \Magento\Framework\Image\AdapterFactory $adapter,
        \Magento\Cms\Model\Wysiwyg\Config $config,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->urlDecoder = $urlDecoder;
        $this->resultRawFactory = $resultRawFactory;
        $this->filter = $filter;
        $this->adapter = $adapter;
        $this->config = $config;
        $this->logger = $logger;
    }


    public function afterExecute(\Magento\Cms\Controller\Adminhtml\Wysiwyg\Directive $subject, $result)
    {
        $directive = $subject->getRequest()->getParam('___directive');
        $directive = $this->urlDecoder->decode($directive);
        $imagePath = $this->filter->filter($directive);
        /** @var \Magento\Framework\Image\Adapter\AdapterInterface $image */
        $image = $this->adapter->create();
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        try {
            $image->open($imagePath);
            if ($image->getMimeType() !== 'image/png') {
                return $result;
            }
            $handler = imagecreatefrompng($imagePath);
            imagesavealpha($handler, true);
            ob_start();
            imagepng($handler);

            $resultRaw->setHeader('Content-Type', $image->getMimeType());
            $resultRaw->setContents(ob_get_clean());
        } catch (\Exception $e) {
            $imagePath = $this->config->getSkinImagePlaceholderPath();
            $image->open($imagePath);
            $resultRaw->setHeader('Content-Type', $image->getMimeType());
            $resultRaw->setContents($image->getImage());
            $this->logger->critical($e);
        }
        return $resultRaw;
    }
}
