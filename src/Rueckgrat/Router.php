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

    /**
     * route
     *
     *
     *
     * @access public
     * @param string $rawRoute
     * @return \Roller\MatchedRoute;
     */
    public function route($rawRoute)
    {

        $route = $this->roller->dispatch($rawRoute);

        return $route->route;
    }

    /**
     * registerAnnotationControllers
     *
     *
     *
     * @access protected
     * @return void
     */
    protected function registerAnnotationControllers()
    {

        $appNamespace = $this->pimple['config']['general.namespace'];
        $appDir = $this->pimple['config']['general.appDir'];
        $controllerPath = $appDir . 'src/' . $appNamespace . '/Controller';
        $directoryHandle = opendir($controllerPath);
            while (false !== ($fileName = readdir($directoryHandle))) {
                $fileNameLength = strlen($fileName);
                if (substr($fileName, $fileNameLength - 4) == '.php') {
                    $controllerName = substr($fileName, 0, -4);
                    $fullControllerName = '\\' . $appNamespace . '\\Controller\\' . $controllerName;
                    $this->roller->importAnnotationMethods( $fullControllerName , '/Action$/' );
                }
            }
            closedir($directoryHandle);

    }

}
