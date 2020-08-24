<?php

namespace LDL\Http\Api\Service\Application\Application\Builder;

use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfigInterface;

class ApplicationBuilderService implements ApplicationBuilderServiceInterface
{
    public static function build(ApplicationConfigInterface $applicationConfig)
    {
        $directory = $applicationConfig->getSource();

        if (!is_dir($directory) && !mkdir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        $dispatcherDir = sprintf(
            '%s%s%s',
            $directory,
            DIRECTORY_SEPARATOR,
            $applicationConfig->getDispatcherFolderName()
        );

        $endpointDir = sprintf(
            '%s%s%s',
            $directory,
            DIRECTORY_SEPARATOR,
            $applicationConfig->getEndpointFolderName()
        );

        $schemaDir = sprintf(
            '%s%s%s',
            $directory,
            DIRECTORY_SEPARATOR,
            $applicationConfig->getSchemaFolderName()
        );

        if (!is_dir($dispatcherDir) && !mkdir($dispatcherDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dispatcherDir));
        }

        if (!is_dir($endpointDir) && !mkdir($endpointDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $endpointDir));
        }

        if (!is_dir($schemaDir) && !mkdir($schemaDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $schemaDir));
        }

        $fromDir = sprintf(
            '%s/Sample/%s',
            __DIR__,
            ApplicationBuilderServiceInterface::DEFAULT_SAMPLE_APPLICATION
        );

        $toDir = sprintf(
            '%s%s%s',
            $directory,
            DIRECTORY_SEPARATOR,
            ApplicationBuilderServiceInterface::DEFAULT_SAMPLE_APPLICATION
        );

        copy($fromDir, $toDir);
    }
}