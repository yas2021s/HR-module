<?php
namespace Hr;

use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\Http\Response as HttpResponse;

class Module
{
    public function onBootstrap(\Laminas\Mvc\MvcEvent $e)
{
    $eventManager = $e->getApplication()->getEventManager();
    $eventManager->attach('route', [$this, 'handleCors'], -10000);
}

public function handleCors(\Laminas\Mvc\MvcEvent $e)
{
    $request = $e->getRequest();
    $response = $e->getResponse();

    // Handle CORS preflight OPTIONS request
    if ($request->getMethod() === 'OPTIONS') {
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
        $headers->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $headers->addHeaderLine('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->setStatusCode(200);
        return $response->send(); // short-circuit the request
    }

    // Allow CORS for all other requests
    $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
}

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
        