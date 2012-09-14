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
     * Holds the names of the javascript files.
     *
     * @access protected
     * @var mixed
     */
    protected $javascripts = array();

    protected $vars = array();

    protected $view = null;

    protected $viewRootPath = null;

    public function __construct($viewRootPath, $config)
    {

        $this->config = $config;
        $appPath = $this->config['general.appDir'] . 'src/' . $this->config['general.namespace'];
        $this->viewRootPath = $appPath . '/View/' . $viewRootPath . '.php';
        $this->view = $viewRootPath;

    }

    public function set($name, $value)
    {

        $this->vars[$name] = $value;

    }

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
        if (isset($this->vars['viewCss']) == false) {
            $this->vars['viewCss'] = array();
        }
        $cssFileName = __DIR__ . 'public/css/' . $this->vars['viewFile'] . '.css';
        if (file_exists($cssFileName) == true) {
            $this->vars['viewCss'][] = $this->vars['viewFile'];
        }
        if (isset($this->vars['viewJs']) == false) {
            $this->vars['viewJs'] = array();
        }
        if (file_exists(__DIR__ . 'public/js/'.$this->vars['viewFile'].'.js')) {
            $this->vars['viewJs'][] = $this->vars['viewFile'];
        }
        extract($this->vars);
        ob_start();
        include($this->viewRootPath);
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;

    }

}
