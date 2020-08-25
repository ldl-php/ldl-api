<?php

namespace LDL\Http\Api\Service\Application\Application\Writer;

use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfigInterface;

class ApplicationWriterService implements ApplicationWriterServiceInterface
{
    /**
     * @var Options\ApplicationWriterOptions
     */
    private $options;

    public function __construct(Options\ApplicationWriterOptions $options = null)
    {
        $this->options = $options ?? Options\ApplicationWriterOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function write(ApplicationConfigInterface $applicationConfig) : void
    {
        $file = sprintf(
            '%s%s%s',
            $applicationConfig->getSource(),
            DIRECTORY_SEPARATOR,
            $this->options->getFile()
        );

        if(false === $this->options->isForce() && true === file_exists($file)){
            $msg = sprintf(
                'File: %s already exists!. Force it to overwrite',
                $file
            );

            throw new Exception\ConfigExistsException($msg);
        }

        file_put_contents(
            $file,
            json_encode($applicationConfig->toArray(), \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\ApplicationWriterOptions
    {
        return clone($this->options);
    }
}