<?php

namespace LDL\Http\Api\Application;

use LDL\Http\Core\Request\RequestInterface;
use LDL\Http\Core\Response\ResponseInterface;
use LDL\Http\Router\Router;
use LDL\Http\Router\Schema\SchemaRepositoryInterface;
use Psr\Container\ContainerInterface;

interface ApplicationInterface
{
    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @return string
     */
    public function getPrefix() : string;

    /**
     * @return bool
     */
    public function isActive() : bool;

    /**
     * Matches an HTTP Request with an application
     *
     * @param RequestInterface $request
     * @return ApplicationInterface
     */
    public function matchHTTPRequest(RequestInterface $request) : ?ApplicationInterface;

    /**
     * Specifies if this application is the default API application
     *
     * Background: When an application is not matched by a request in the application collection
     * this application will be used instead.
     *
     * @return bool
     */
    public function isDefault() : bool;

    /**
     * Returns the group of schemas defined for this application
     *
     * @return SchemaRepositoryInterface
     */
    public function getSchemaRepository() : ?SchemaRepositoryInterface;

    /**
     * @param ContainerInterface $container
     * @return Router
     */
    public function getRouter(ContainerInterface $container) : Router;
}