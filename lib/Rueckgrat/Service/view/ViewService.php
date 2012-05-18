<?php

/**
 * View Class
 * 
 * This class can pass variables to view templates and 
 * render templates.
 * 
 * @author  Alexander Feil
 * @author  Nils Abegg
 * @version 1.0
 * @package Service
 */
namespace Rueckgrat\Service\View;

class ViewService
{
    /**
     * Vars for the view
     * 
     * @var mixed
     */
    protected $vars = array();
    
    /**
     * Filname for the view file
     * 
     * @var string
     */
    protected $file = null;
    
    /**
     * Root of the views folder
     * 
     * @var string
     */
    protected $viewRootPath = null;
    
    /**
     * Construtor
     * 
     * @param  string $viewRootPath
     * @return void
     */
    public function __construct($viewRootPath = '../../../app/views/') 
    {
        $this->viewRootPath = __DIR__ . '/' . $viewRootPath;
    }
    
    /**
     * Set vars for view
     * 
     * @param string $name
     * @param mixed  $value 
     */
    public function set($name, $value) 
    {
        if (is_object($value)) {
            $this->vars[$name] = $value->fetch();        
        }
        else {
            $this->vars[$name] = $value;
        }
    }

    /**
     * Render view
     * 
     * @param  string $file
     * @return string
     */
    public function render($file = null) 
    {
        if (!$file) {
            $file = $this->file;
        }
        extract($this->vars);
        ob_start();
        include($this->viewRootPath . $file . '.php'); 
        $contents = ob_get_contents();
        ob_end_clean();
        
        return $contents;
    }   
}