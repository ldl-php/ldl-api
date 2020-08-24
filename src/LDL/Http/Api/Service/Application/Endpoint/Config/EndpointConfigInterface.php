<?php

namespace LDL\Http\Api\Service\Application\Endpoint\Config;

interface EndpointConfigInterface extends \JsonSerializable
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
    public function getVersion(): string;

    /**
     * @return string
     */
    public function getUrlPrefix(): string;

    /**
     * @return string
     */
    public function getAuthenticationType(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getDispatcherClass(): string;

    /**
     * @return string
     */
    public function getRequestMethod(): string;

    /**
     * @return string
     */
    public function getRequestHeadersSchemaRepository(): string;

    /**
     * @return string
     */
    public function getRequestParamsSchemaRepository(): string;

    /**
     * @return string
     */
    public function getResponseContentType(): string;
}