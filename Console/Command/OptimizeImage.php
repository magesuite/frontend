<?php

namespace MageSuite\Frontend\Console\Command;

class OptimizeImage extends \Symfony\Component\Console\Command\Command
{
    const IMAGE_FILE_ARGUMENT = 'image_file';

    /**
     * @var \MageSuite\Frontend\Service\Image\OptimizerFactory
     */
    private $optimizerFactory;

    public function __construct(\MageSuite\Frontend\Service\Image\OptimizerFactory $optimizerFactory)
    {
        parent::__construct();

        $this->optimizerFactory = $optimizerFactory;
    }

    protected function configure()
    {
        $this->setName('image:optimize')
            ->setDescription('Optimize images');

        $this->addArgument(
            self::IMAGE_FILE_ARGUMENT,
            \Symfony\Component\Console\Input\InputArgument::REQUIRED,
            'Image file relative to main directory'
        );
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        $imageFilePath = $input->getArgument(self::IMAGE_FILE_ARGUMENT);
        $imageFilePath = sprintf('%s/%s', BP, $imageFilePath);

        if(!file_exists($imageFilePath)) {
            throw new \Symfony\Component\Filesystem\Exception\FileNotFoundException(sprintf('File %s was not found', $imageFilePath));
        }

        $optimizer = $this->optimizerFactory->create();

        $optimizer->optimize($imageFilePath);

        $output->writeln(sprintf('<info>Image %s was optimized</info>', $imageFilePath));
    }
}
