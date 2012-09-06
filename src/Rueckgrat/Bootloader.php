<?php

namespace Rueckgrat;

class Bootloader
{

    /**
    * config
    *
    * Holds the configuration values in an array.
    *
    * @var mixed
    */
    protected $config = null;

    protected $pimple = null;

    public function __construct(\Pimple $pimple)
    {

        $this->pimple = $pimple;
        $this->config = $pimple['config'];
        $this->setReporting();
        $appLoader = new Autoloader($this->config['general.namespace'], __DIR__ . '/../../../../../src');
        $appLoader->register();

    }

    /**
     * setReporting
     *
     * Sets the reporting options for the application
     * according to your defined debug status in the
     * config.ini
     *
     * @return
     */
    public function setReporting()
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

    protected function stripSlashesDeep($value)
    {

        $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);

        return $value;

    }

    public function unregisterGlobals()
    {

        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                $this->unregisterGlobal($value);
            }
        }

    }

    public function removeMagicQuotes()
    {

        if (get_magic_quotes_gpc() == true) {
            $_GET    = $this->stripSlashesDeep($_GET   );
            $_POST   = $this->stripSlashesDeep($_POST  );
            $_COOKIE = $this->stripSlashesDeep($_COOKIE);
        }

    }

    /** Secondary Call Function **/

    public function performAction($controller, $action, $queryString = null, $render = 0)
    {

            $controllerName = ucfirst($controller).'Controller';
            $dispatch = new $controllerName($action, $config);
            $dispatch->render = $render;

            return call_user_func_array(array($dispatch, $action), $queryString);

    }


    protected function routeURL($url)
    {

        global $routing;
        foreach ($routing as $pattern => $result) {
            if (preg_match( $pattern, $url)) {
                return preg_replace( $pattern, $result, $url );
            }
        }

        return ($url);

    }

    public function callHook()
    {

        global $url;
        global $default;

        $queryString = array();

	if (!isset($url)) {
            $controller = $default['controller'];
            $action = $default['action'];
	} else {
            $url = str_replace('redirect:/public/index.php/', '', $url);
            $url = $this->routeURL($url);
            $urlArray = array();
            $urlArray = explode("/", $url);
            $controller = $urlArray[0];
            array_shift($urlArray);
            if (isset($urlArray[0])) {
                $action = $urlArray[0];
                array_shift($urlArray);
            } else {
                $action = 'index'; // Default Action
            }
            $queryString = $urlArray;
	}
	$controllerName = ucfirst($controller);
        $controller = '\\' . $this->config['general.namespace'] . '\\Controller\\' . $controllerName;
	$dispatch = new $controller($action, $this->pimple);
	if ((int) method_exists($controller, $action)) {
            call_user_func_array(array($dispatch, "beforeAction"), $queryString);
            call_user_func_array(array($dispatch, $action), $queryString);
            call_user_func_array(array($dispatch, "afterAction"), $queryString);
        } else {

        }

    }

    protected function unregisterGlobal($value)
    {

        foreach ($GLOBALS[$value] as $key => $var) {
            if ($var === $GLOBALS[$key]) {
                unset($GLOBALS[$key]);
            }
        }

    }
}