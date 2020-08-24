<?php

require './autoload.php';

use LDL\Http\Api\Application\ApplicationManager;
use LDL\Http\Core\Request\Request;
use LDL\Http\Core\Response\Response;
use LDL\Http\Api\Kernel\Kernel;
use LDL\Http\Router\Router;
use MyProject\Application\TestApplication;

$router = new Router(Request::createFromGlobals(), new Response());
$appManager = new ApplicationManager();

$testApp = new TestApplication(
  'test',
    'test',
    $router,
    true
);

$appManager->append($testApp);

$kernel = new Kernel($appManager);

$response = new Response();

$kernel->dispatch()->send();