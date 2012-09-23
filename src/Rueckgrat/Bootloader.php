<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

/**
 * Bootloader
 *
 * This class handles bootstrapping
 * of the application
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Bootstrap
 */
class Bootloader
{

    /**
    * config
    *
    * Holds the configuration values in an array.
    *
    * @access protected
    * @var mixed
    */
    protected $config = null;

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
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param  \Pimple $pimple
     * @return void
     */
    public function __construct(\Pimple $pimple)
    {

        $this->pimple = $pimple;
        $this->config = $pimple['config'];
        $this->setReporting();
        $appDir = $this->config['general.appDir'] . 'src';
        $appLoader = new Autoloader($this->config['general.namespace'], $appDir);
        $appLoader->register();
        $this->router = new Router($this->pimple);

    }

    /**
     * route
     *
     *
     *
     * @access public
     * @return void
     * @todo Add parameter support!!!
     */
    public function route()
    {

        $request = $this->pimple['request'];
        $route = $this->router->route($request->getPathInfo());
        $controllerAndAction = $route['callback'];
        $controllerName = $controllerAndAction[0];
        $actionName = $controllerAndAction[1];
        $controller = new $controllerName($actionName, $this->pimple);
        $controller->$actionName();
    }

    /**
     * setReporting
     *
     * Sets the reporting options for the application
     * according to your defined debug status in the
     * config.ini
     *
     * @access public
     * @return void
     * @todo ErrorLogging
     */
    protected function setReporting()
    {

        if ($this->config['general.debug'] == true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', '../log/error.log');
        }

    }

    /**
     * routeUrl
     *
     * Routes the given URL.
     *
     * @access protected
     * @global mixed $routing
     * @param string $url
     * @return string
     * @todo Understand routeUrl()
     */
    protected function routeUrl($url)
    {

        global $routing;
        foreach ($routing as $pattern => $result) {
            if (preg_match( $pattern, $url)) {
                return preg_replace( $pattern, $result, $url );
            }
        }

        return ($url);

    }

    /**
     * callHook
     *
     * Calls the controller for the route.
     *
     * @access public
     * @global string $url
     * @global string $default
     * @return void
     * @todo Refactor Method and remove globals
     */
    public function callHook()
    {

        global $url;
        global $default;

        $queryString = array();

        if (!isset($url)) {
            $controller = $default['controller'];
            $action = $default['action'];
        } else {
            //$url = str_replace('redirect:/public/index.php/', '', $url);
            $url = $this->routeURL($url);
            $urlArray = explode("/", $url);
            $controller = $urlArray[0];
            array_shift($urlArray);
            if (isset($urlArray[0])) {
                $action = $urlArray[0];
                array_shift($urlArray);
            } else {
                $action = 'index'; // Default Action
            }
            $queryString = $urlArray;
        }
        $controllerName = ucfirst($controller);
        $controller = '\\' . $this->config['general.namespace'] . '\\Controller\\' . $controllerName;
        $dispatch = new $controller($action, $this->pimple);
        if ((int) method_exists($controller, $action)) {
            call_user_func_array(array($dispatch, "beforeAction"), $queryString);
            call_user_func_array(array($dispatch, $action), $queryString);
            call_user_func_array(array($dispatch, "afterAction"), $queryString);
        } else {

        }

    }

}
