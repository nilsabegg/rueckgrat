<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

use \Roller\Router as Roller;

/**
 * Routing
 *
 * This class is the parent class for the routing class(es)
 * of the application.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Routing
 */
class Router
{

    /**
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param  \Pimple $pimple
     * @return void
     */
    public function __construct(\Pimple $pimple, $type = 'annotation')
    {

        $this->pimple = $pimple;
        $this->roller = new Roller();
        $this->type = $type;
        if ($this->type == 'annotation') {
            $this->registerAnnotationControllers();
        }

    }

    public function route($rawRoute)
    {
        $route = $this->roller->dispatch($rawRoute);
        print_r($route); // returns 'index'

    }

    protected function registerAnnotationControllers()
    {

        $appNamespace = $this->pimple['config']['general.namespace'];
        $appDir = $this->pimple['config']['general.appDir'];
        $controllerPath = $appDir . 'src/' . $appNamespace . '/Controller';
        $directoryHandle = opendir($controllerPath);
            while (false !== ($fileName = readdir($directoryHandle))) {
                echo substr($fileName, 0, -4) . "\n";
                if (substr($fileName, 0, -4) == '.php') {
                    $controllerName = str_replace($fileName, '', '.php');
                    $fullControllerName = '\\' . $appNamespace . '\\Controller\\' . $controllerName;
                    echo $fullControllerName;
                    $this->router->importAnnotationMethods( $fullControllerName , '/Action$/' );
                }
            }
            closedir($directoryHandle);

    }

}
