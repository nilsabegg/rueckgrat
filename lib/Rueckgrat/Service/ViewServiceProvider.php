<?php

/**
 * View %Service Provider
 * 
 * This class provides a View class to render 
 * templates as a service.
 * 
 * @author  Alexander Feil
 * @author  Nils Abegg
 * @version 1.0
 * @package Service
 */
namespace Rueckgrat\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Rueckgrat\Service\View\ViewService;

class ViewServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the service
     *
     * @param  Application $app
     * @return ViewService 
     */
    public function register(Application $app) 
    {
        $app['view'] = function () use ($app) {
            return new ViewService($app['view.root_path']);	
        };
    }
}

