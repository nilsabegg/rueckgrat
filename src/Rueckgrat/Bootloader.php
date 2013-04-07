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
     * @var \Rueckgrat\Router
     */
    protected $router = null;

    /**
     * session
     *
     * Holds the session object.
     *
     * @access protected
     * @var Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session = null;

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
        $this->session = $this->pimple['session'];
        $this->session->start();
        $this->session->set('language', $this->session->get('language', $this->config['general.default_language']));
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
        $route = $this->router->route($request->getPathInfo());
        $controllerAndAction = $route['callback'];
        $controllerName = $controllerAndAction[0];
        $actionName = $controllerAndAction[1];
        $controller = new $controllerName($actionName, $this->pimple);
        $controller->beforeAction();
        $parameters = $route['vars'];
        if (is_array($parameters) == true) {
            call_user_func_array(array($controller, $actionName), $parameters);
        } else {
            $controller->$actionName();
        }
        $controller->afterAction();

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