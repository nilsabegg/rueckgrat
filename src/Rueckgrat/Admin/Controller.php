<?php

namespace Rueckgrat\Admin;

use Rueckgrat\View\View as View;

class Controller extends \Rueckgrat\Controller\Controller
{

    /**
     * isSecured
     *
     * Indicates if the controller perfoms actions
     * for authenticated users only.
     *
     * @var boolean
     */
    protected $isSecured = true;

    public function __construct($action, $pimple)
    {
        parent::__construct($action, $pimple);
        $this->createNavigation();
    }

    /**
     * createNavigation
     *
     * Creates the navigation bar for the admin section
     *
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
     * @return void
     */
    protected function createTemplate()
    {

        $this->template = new View('admin', $this->config);

    }

}