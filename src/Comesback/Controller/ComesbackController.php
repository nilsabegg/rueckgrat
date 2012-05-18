<?php

/**
 * %Rueckgrat %Controller 
 * 
 * This class manages the main bootstrapping and calls
 * the right sub controller
 * 
 * - Silex Documentation: http://silex.sensiolabs.org/doc/providers.html#controllers-providers
 * - Symfony Route API:   http://api.symfony.com/2.0/Symfony/Component/Routing/Route.html
 *
 * @author  Alexander Feil
 * @author  Nils Abegg
 * @version 1.0
 * @package Controller
 */
namespace Comesback\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Rueckgrat\Base\ControllerProvider;

class ComesbackController extends ControllerProvider implements ControllerProviderInterface
{
    
    /**
     * Connect controller to route
     * 
     * @param  Application          $app
     * @return ControllerCollection 
     */
    public function connect(Application $app)
    {
        
        if (file_exists($filename) == true && $app['debug'] == false)
        {
            
        }
        else
        {
            
        }
        foreach ($controllerNames as $controllerName)
        {
            $controllerClassName = '\\Comesback\\Controller\\' . $controllerName . 'Controller';
            $app->mount('/' . $controllerName, new $controllerClassName());
        }
        
    }

}