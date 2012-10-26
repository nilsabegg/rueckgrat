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
     * router
     *
     * Holds the router object.
     *
     * @access protected
     * @var type \Rueckgrat\Router
     */
    protected $router = null;

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
     * Routes the request and calls the action
     * of the matched controller.
     *
     * @access public
     * @return void
     * @todo Add parameter support!!!
     */
    public function route()
    {

        $request = $this->pimple['request'];
        echo $request->getPathInfo();
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

}