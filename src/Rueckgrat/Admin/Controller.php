<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat\Admin;

use \Roller\Annotations\Route as Route;
use Rueckgrat\View\View as View;

/**
 * Controller
 *
 * This class is the blueprint for all the backend controllers
 * of the application.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @subpackage Admin
 * @category Controller
 */
abstract class Controller extends \Rueckgrat\Controller\Controller
{

    /**
     * isSecured
     *
     * Indicates if the controller perfoms actions
     * for authenticated users only.
     *
     * @access protected
     * @var boolean
     */
    protected $isSecured = false;

    /**
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param type $action
     * @param type $pimple
     * @return void
     */
    public function __construct($action, $pimple)
    {
        parent::__construct($action, $pimple);
        $this->createNavigation();
    }

    /**
     * createNavigation
     *
     * Creates the navigation bar for the admin section.
     *
     * @access protected
     * @return void
     */
    protected function createNavigation()
    {
        $this->pimple['view.rootPath'] = 'admin/_navigation';
        $navigationView = $this->pimple['view'];
        $navigationView->set('rootUrl', $this->getRootUrl());
        $this->template->set('navigation', $navigationView->render());
    }

    /**
     * createTemplate
     *
     * Creates the page template.
     *
     * @access protected
     * @return void
     */
    protected function createTemplate()
    {

        $this->pimple['view.rootPath'] = 'admin';
        $view = $this->pimple['view'];
        $this->template = $view;

    }

}