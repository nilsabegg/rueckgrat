<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat\View;

/**
 * View
 *
 * This class handles the templates of
 * the application.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @author  Alexander Feil <rueckgrat@alexanderfeil.me>
 * @version 0.1
 * @package Rueckgrat
 * @subpackage View
 * @category View
 */
class View
{

    /**
     * javascripts
     *
     * Holds the names of the javascript files
     * for the template.
     *
     * @access protected
     * @var mixed
     */
    protected $javascripts = array();

    /**
     * vars
     *
     * Holds the variables for the template.
     *
     * @access protected
     * @var mixed
     */
    protected $vars = array();

    /**
     * view
     *
     * Holds the name of the template.
     *
     * @access protected
     * @var string
     */
    protected $view = null;

    /**
     * viewRootPath
     *
     * Holds the path to the template.
     *
     * @access protected
     * @var string
     */
    protected $viewRootPath = null;

    /**
     * __construct
     *
     * Constructs the object.
     *
     * @param string $viewRootPath
     * @param \Pimple $pimple
     * return void
     */
    public function __construct($viewRootPath, $pimple)
    {

        $this->pimple = $pimple;
        $this->config = $this->pimple['config'];
        $appPath = $this->config['general.appDir'] . 'src/' . $this->config['general.namespace'];
        $this->viewRootPath = $appPath . '/View/' . $viewRootPath . '.php';
        $this->view = $viewRootPath;

    }

    public function __toString()
    {
        return $this->render();
    }
    /**
     * set
     *
     * Sets a variable for the template.
     *
     * @access protected
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set($name, $value)
    {

        $this->vars[$name] = $value;

    }

    /**
     * set
     *
     * Sets a name of a Javascript file for the template.
     *
     * @access protected
     * @param string $value
     * @return void
     */
    public function setJs($value)
    {

        $this->javascripts[] = $value;

    }

    /**
     * render
     *
     * Renders the Javascript of the view object.
     *
     * @access public
     * @return string
     */
    public function renderJs()
    {

        $html = '';
        foreach ($this->javascripts as $javascript) {
            $source = 'http://' . $_SERVER['SERVER_NAME'] . '/js/'. $javascript . '.js';
            $html .= '<script src="' . $source . '" type="text/javascript"></script>';
        }

        return $html;

    }

    /**
     * render
     *
     * Renders the template of the view object.
     *
     * @access public
     * @return string
     */
    public function render()
    {

        $jsPath = $this->config['general.appDir'] . 'public/js/' . $this->view . '.js';
        if (file_exists($jsPath) == true) {
            $this->setJs($this->view);
        }
        $this->vars['viewFile'] = basename($_SERVER['PHP_SELF'], '.php');
        if (isset($this->vars['viewJs']) == false) {
            $this->vars['viewJs'] = array();
        }
        if (file_exists(__DIR__ . 'public/js/'.$this->vars['viewFile'].'.js')) {
            $this->vars['viewJs'][] = $this->vars['viewFile'];
        }
        $this->vars['session'] = $this->pimple['session'];
        extract($this->vars);
        ob_start();
        include($this->viewRootPath);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;

    }

}
