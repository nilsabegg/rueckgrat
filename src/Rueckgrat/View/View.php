<?php

namespace Rueckgrat\View;

class View
{

    protected $vars = array();
    protected $file = null;
    protected $viewRootPath = null;
    protected $javascripts = array();
    protected $view = null;

    public function __construct($viewRootPath, $config)
    {

        $this->config = $config;
        $this->viewRootPath = $this->config['general.appDir'] . 'src/' . $this->config['general.namespace'] . '/View/' . $viewRootPath . '.php';
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

    public function renderJs()
    {
        $html = '';
        foreach ($this->javascripts as $javascript) {
            $html .= '<script src="http://' . $_SERVER['SERVER_NAME'] . '/js/'. $javascript . '.js" type="text/javascript"></script>';
        }

        return $html;

    }

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
        if (!isset($this->vars['viewJs'])) $this->vars['viewJs'] = array();
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
