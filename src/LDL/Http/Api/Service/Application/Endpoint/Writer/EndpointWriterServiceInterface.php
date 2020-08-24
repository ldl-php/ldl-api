<?php

namespace LDL\Http\Api\Service\Application\Endpoint\Writer;

use LDL\Http\Api\Service\Application\Endpoint\Config\EndpointConfigInterface;

interface EndpointWriterServiceInterface
{
    /**
     * Write application configuration
     *
     * @param EndpointConfigInterface $endpointConfig
     * @throws Exception\ConfigExistsException
     */
    public function write(EndpointConfigInterface $endpointConfig) : void;

    /**
     * @return Options\EndpointWriterOptions
     */
    public function getOptions(): Options\EndpointWriterOptions;
}