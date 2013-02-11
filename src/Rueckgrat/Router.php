<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

use \Roller\Router as Roller;

/**
 * Router
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
     * pimple
     *
     * Holds the Pimple dependency injection container.
     *
     * @access protected
     * @var \Pimple
     */
    protected $pimple = null;

    /**
     * roller
     *
     *
     *
     * @access protected
     * @var type
     */
    protected $roller = null;

    /**
     * type
     *
     *
     *
     * @access protected
     * @var string
     */
    protected $type = null;

    /**
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param  \Pimple $pimple
     * @param string $type
     * @return void
     */
    public function __construct(\Pimple $pimple, $type = 'annotation')
    {

        $this->pimple = $pimple;
        $this->roller = new Roller(null, array(
            'route_class' => '\\Rueckgrat\\Route'
        ));
        $this->type = $type;
        if ($this->type == 'annotation') {
            $this->registerAnnotationControllers();
        }

    }

    /**
     * route
     *
     * Returns the matched route for raw route.
     * Expected input ist something like:
     * 'project/edit/1'
     * This is wrong:
     * 'dir/project/edit/1
     *
     * @access public
     * @param string $rawRoute
     * @return \Roller\MatchedRoute;
     */
    public function route($rawRoute)
    {

        $strippedRawRoute = $this->stripPathFromUrl();
        if ($strippedRawRoute == '') {
            $rawRouteWithoutPath = $rawRoute;
        } else {
            $rawRouteWithoutPath = str_replace('/' . $this->stripPathFromUrl(), '', $rawRoute);
        }
        if ($rawRouteWithoutPath == '') {
            $rawRouteWithoutPath = '/';
        }
        $route = $this->roller->dispatch($rawRouteWithoutPath);
        echo '<pre>';
        print_r($route);
        return $route->route;

    }

    /**
     * stripPathFromUrl
     *
     * Returns the path which is stripped out of the URL.
     *
     * @access protected
     * @return string
     */
    protected function stripPathFromUrl()
    {

        $urlWithoutHttp = str_replace('http://', '', $this->pimple['config']['general.url']);
        $urlWithoutHttpParts = explode('/', $urlWithoutHttp, 2);
        if (isset($urlWithoutHttpParts[1]) == true) {
            return $urlWithoutHttpParts[1];
        } else {
            return '';
        }

    }

    /**
     * registerAnnotationControllers
     *
     * Registers the controllers of the application
     * for the annotation reader.
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
                $this->roller->importAnnotationMethods($fullControllerName, '/Action$/');
            }
        }
        closedir($directoryHandle);

    }

}
