<?php

namespace LDL\Http\Api\Kernel;

use LDL\Http\Api\Application\ApplicationManager;
use LDL\Http\Core\Request\Request;
use LDL\Http\Core\Request\RequestInterface;
use LDL\Http\Core\Response\Response;
use LDL\Http\Core\Response\ResponseInterface;
use LDL\Http\Router\Route\Factory\RouteFactory;
use LDL\Http\Router\Route\Group\RouteGroup;
use LDL\Http\Router\Router;
use Psr\Container\ContainerInterface;

class Kernel
{

    /**
     * @var ApplicationManager
     */
    private $appManager;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ApplicationManager $appManager,
        ContainerInterface $container = null,
        RequestInterface $request = null
    )
    {
        $this->request = $request ?? Request::createFromGlobals();
        $this->appManager = $appManager;
        $this->container = $container;
    }

    public function dispatch(bool $fallbackToDefault=true) : ResponseInterface
    {
        $application = $this->appManager->matchHTTPRequest($this->request);

        if(null === $application && $fallbackToDefault){
            $application = $this->appManager->getDefaultApplication();
        }

        if(null === $application){
            $response = new Response();
            $response->setContent('Application not found');
            $response->setStatusCode(ResponseInterface::HTTP_CODE_NOT_FOUND);

            return $response;
        }

        return $application->getRouter($this->container)->dispatch();
    }
}