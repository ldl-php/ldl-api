<?php

namespace LDL\Http\Api\Service\Application\Endpoint\Writer;

use LDL\Http\Api\Service\Application\Endpoint\Config\EndpointConfigInterface;

class EndpointWriterService implements EndpointWriterServiceInterface
{
    /**
     * @var Options\EndpointWriterOptions
     */
    private $options;

    public function __construct(Options\EndpointWriterOptions $options = null)
    {
        $this->options = $options ?? Options\EndpointWriterOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function write(EndpointConfigInterface $endpointConfig) : void
    {
        $file = $this->options->getRealPath();

        $config = [];

        if(true === file_exists($file)){
            $config = json_decode(
                file_get_contents($file),
                true,
                512,
                \JSON_THROW_ON_ERROR
            );
        }

        $config['routes'][] = $endpointConfig->toArray();
        $jsonConfig = json_encode($config, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES);

        file_put_contents($file, $jsonConfig);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\EndpointWriterOptions
    {
        return clone($this->options);
    }
}