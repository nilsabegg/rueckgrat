<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat\Controller;

use \Roller\Annotations\Route as Route;
use \Symfony\Component\Yaml\Parser as Yaml;
use Rueckgrat\View\View as View;

/**
 * Controller
 *
 * This class is the blueprint for all the frontend controllers
 * of the application.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @subpackage Controller
 * @category Controller
 */
abstract class Controller
{

    /**
     * action
     *
     * Holds the name of the called action.
     *
     * @access protected
     * @var type
     */
    protected $action = null;

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
     * entityManager
     *
     * Holds the Doctrine entity manager.
     *
     * @access protected
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;

    /**
     * hasModel
     *
     * Indiccates if the controller has a
     * related model.
     *
     * @access protected
     * @var boolean
     */
    protected $hasModel = true;

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
     * pimple
     *
     * Holds the Pimple dependency injection container.
     *
     * @access protected
     * @var \Pimple
     */
    protected $pimple = null;

    /**
     * rootUrl
     *
     * Holds the root URL of the application.
     *
     * @access protected
     * @var string
     */
    protected $rootUrl = null;

    /**
     * template
     *
     * Holds the main template for the page.
     * You need to overwrite this, if you don't
     * want to use the default page template in
     * 'app/view/index.php'.
     *
     * @access protected
     * @var View
     */
    protected $template = null;

    /**
     * user
     *
     * Holds the Doctrine entity of the current user.
     *
     * @access protected
     * @var Object
     */
    protected $user = null;

    /**
     * view
     *
     * Holds the view template for the page.
     *
     * @access protected
     * @var View
     */
    protected $view = null;

    /**
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param string $action
     * @param \Pimple $pimple
     * @return void
     */
    public function __construct($action, $pimple)
    {

        $this->pimple = $pimple;
        $this->config = $this->pimple['config'];
        $this->action = $action;
        $this->createTemplate();
        $this->entityManager = $this->pimple['entityManager'];
        if ($this->secured == true && isset($_SESSION['id']) == false) {
            $this->redirect('user/login');
        } elseif (isset($_SESSION['id']) == true) {
            $repositoryName = '\\' . $this->config['general.namespace'] . '\\Model\\Repository\\User';
            $this->userRepository = new $repositoryName($this->pimple);
            $this->user = $this->userRepository->create(intval($_SESSION['id']));
        }
        if ($this->hasModel !== false) {
            $namespace = '\\' . $this->config['general.namespace'] . '\\Model\\Repository\\';
            $repositoryName = $namespace . $this->getControllerName($this);
            $this->repository = new $repositoryName($this->pimple);
        }
        if ($this->config['general.i18n'] == true) {
            $userLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $yamlParser = new Yaml();
            $this->strings = $yamlParser->parse(file_get_contents($this->config['general.appDir'] . 'etc/i18n/' . $userLanguage . '.yml'));
            print_r($this->strings);
            $_SESSION['language'] = $userLanguage;

        }
        $this->rootUrl = $this->getRootUrl();

}

    /**
     * afterAction
     *
     * This action is called after the actual
     * requested controller action. You need
     * to overwrite this in your controller.
     *
     * @access public
     * @return void
     */
    public function afterAction()
    {

    }

    /**
     * beforeAction
     *
     * This action is called before the actual
     * requested controller action. You need
     * to overwrite this in your controller.
     *
     * @access public
     * @return void
     */
    public function beforeAction()
    {

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

        $this->template = new View('index', $this->config);

    }

    /**
     * renderPage
     *
     * Renders the whole page template.
     *
     * @access protected
     * @param boolean $ajax
     * @return void
     * @todo Implement response object
     */
    protected function renderPage($ajax = false)
    {

        if ($ajax == false) {
            $this->template->set('rootUrl', $this->rootUrl);
            $this->template->set('content', $this->view->render());
            $this->template->set('js', $this->view->renderJs());
            echo $this->template->render();
        } else {
            $this->template = $this->view;
            $this->template->set('js', $this->view->renderJs());

            return $this->template->render();
        }

    }

    /**
     * renderComponent
     *
     * Renders a component action.
     * A component is a partial which has an
     * associated controller action.
     *
     * @access protected
     * @param string $path
     * @return string
     */
    protected function renderComponent($path)
    {

        $pathParts = explode('/', $path);
        $controllerName = '\\' . $this->config['general.namespace'] . '\\Controller\\' . ucfirst($pathParts[0]);
        $controller = new $controllerName($pathParts[0], $this->pimple);
        $action = $pathParts[1];

        return $controller->$action($pathParts[2]);

    }

    /**
     * redirect
     *
     * Redirects to framework internal urls.
     *
     * @access protected
     * @param string $path
     * @return void
     * @todo Implement response object
     */
    protected function redirect ($path)
    {

        header('Location: ' . $this->getUrl($path));

    }

    /**
     * getControllerName
     *
     * Returns the name of an entity's table.
     *
     * @access protected
     * @param  Controller $controller
     * @return string
     */
    protected function getControllerName(Controller $controller)
    {
        $controllerClassName = get_class($controller);
        $namespace = $this->config['general.namespace'];
        $fullControllerName = str_replace($namespace . '\\Controller\\', '', $controllerClassName);

        return $fullControllerName;
    }

    /**
     * getUrl
     *
     * Returns a application internal URL.
     *
     * @access protected
     * @param string $name
     * @return string
     */
    protected function getUrl($name = '')
    {

        $url = $this->config['general.url'] . '/' . $name;

        return $url;

    }

    /**
     * getRootUrl
     *
     * Returns the root URL of the application.
     *
     * @access protected
     * @return string
     */
    protected function getRootUrl()
    {

        $url = $this->getUrl();
        $this->rootUrl = $url;

        return $this->rootUrl;

    }

    /**
     * set
     *
     * Sets a variable for the view Template
     *
     * @access protected
     * @param string $name
     * @param mixed $value
     * @return void
     */
    protected function set($name, $value)
    {

        $this->view->set($name, $value);

    }

}