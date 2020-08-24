<?php

namespace MyProject\Application\Dispatcher\User\Crud;

use LDL\Http\Core\Request\RequestInterface;
use LDL\Http\Core\Response\ResponseInterface;
use LDL\Http\Router\Route\Dispatcher\RouteDispatcherInterface;
use LDL\Http\Router\Route\Parameter\ParameterCollection;

class UpdateDispatcher implements RouteDispatcherInterface
{
    public function dispatch(
        RequestInterface $request,
        ResponseInterface $response,
        ParameterCollection $parameters = null
    )
    {
        die(__CLASS__);
    }
}