<?php

namespace LDL\Http\Api\Service\Application\Application\Writer;

use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfigInterface;

interface ApplicationWriterServiceInterface
{
    /**
     * Write application configuration
     *
     * @param ApplicationConfigInterface $applicationConfig
     * @throws Exception\ConfigExistsException
     */
    public function write(ApplicationConfigInterface $applicationConfig) : void;

    /**
     * @return Options\ApplicationWriterOptions
     */
    public function getOptions(): Options\ApplicationWriterOptions;
}