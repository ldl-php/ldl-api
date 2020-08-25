<?php

namespace LDL\Http\Api\Service\Application\Application\Config;

interface ApplicationConfigInterface extends \JsonSerializable
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function jsonSerialize(): array;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPrefix(): string;

    /**
     * @return string
     */
    public function getSource(): string;

    /**
     * @return string
     */
    public function getDispatcherFolderName(): string;

    /**
     * @return string
     */
    public function getEndpointFolderName(): string;

    /**
     * @return string
     */
    public function getSchemaFolderName(): string;
}