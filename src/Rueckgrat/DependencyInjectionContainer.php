<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

use Rueckgrat\View\View as View;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\HttpFoundation\Session\Session as Session;

/**
 * DependencyInjectionContainer
 *
 * This class handles the templates of
 * the application.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Dependency Injection
 */
class DependencyInjectionContainer extends \Pimple
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
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param mixed  $config
     * @return void
     */
    public function __construct($config)
    {

        $this->config = $config;
        $this->registerConfig();
        $this->registerDatabase();
        $this->registerRequest();
        $this->registerResponse();
        $this->registerSession();
        $this->registerUser();
        $this->registerView();

    }

    /**
     * registerConfig
     *
     * Registers the configuration values for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerConfig()
    {

        $newConfig = array();
        foreach ($this->config as $configSection => $configParameters) {
            $prefix = $configSection;
            foreach ($configParameters as $configOption => $configParameter) {
                $newConfig[$prefix . '.' . $configOption] = $configParameter;
            }
        }
        $this['config'] = $newConfig;

    }

    /**
     * registerDatabase
     *
     * Registers the Doctrine entity manager for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerDatabase()
    {

        $this['entityManager'] = $this->share(function($pimple) {
            $database = new Database($pimple['config']);
            $entityManager = $database->getEntityManager();

            return $entityManager;
        });

    }

    /**
     * registerRequest
     *
     * Registers the request object for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerRequest()
    {

        $this['request'] = $this->share(function() {
            $request = Request::createFromGlobals();

            return $request;
        });

    }

    /**
     * registerView
     *
     * Registers the response object for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerResponse()
    {

        $this['response'] = function() {
            $response = new Response();
            $response->headers->set('Content-Type', 'text/html');
            $response->setStatusCode(200);

            return $response;
        };

    }

    /**
     * registerSession
     *
     * Registers the session object for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerSession()
    {

        $this['session'] = $this->share(function() {
            $session = new Session();

            return $session;
        });

    }

    /**
     * registerView
     *
     * Registers the view object for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerUser()
    {

        $this['user'] = $this->share(function($pimple) {
            $className = '\\' . $pimple['config']['general.namespace'] . '\\Library\\User';
            $user = new $className($pimple);

            return $user;
        });

    }

    /**
     * registerView
     *
     * Registers the view object for access
     * via the Dependency Injection Container
     *
     * @access protected
     * @return void
     */
    protected function registerView()
    {

        $this['view.rootPath'] = 'index/index';
        $pimple = $this;
        $this['view'] =  function($pimple) {
            $view = new View($pimple['view.rootPath'], $pimple);

            return $view;
        };

    }



}
