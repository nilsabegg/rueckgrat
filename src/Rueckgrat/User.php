<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

/**
 * User
 *
 * This class handles bootstrapping
 * of the application
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Bootstrap
 */
class User
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

    protected $language = null;

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

    }
    public function getEntity()
    {

    }
    public function getLanguage()
    {
        
    }
    public function setEntity()
    {

    }
    public function setLanguage($language)
    {
        $this->language = $language;
    }

}