<?php

namespace Rueckgrat;

use Rueckgrat\View\View as View;

class DependencyInjectionContainer extends \Pimple
{

    public function __construct($config) {
        $newConfig = array();
        foreach ($config as $configSection => $configParameters) {
            $prefix = $configSection;
            foreach ($configParameters as $configOption => $configParameter) {
                $newConfig[$prefix . '.' . $configOption] = $configParameter;
            }
        }
        $this['config'] = $newConfig;
        $this['view.rootPath'] = 'index/index';
        $this['view'] =  function ($config) {
            return new View($config['view.rootPath'], $config['config']);
        };

        $this['databaseHandler'] = $this->share(function ($config) {
            $dsn = 'mysql:host=' . $config['config']['database.host'] . ';dbname=' . $config['config']['database.database'];
            return new \PDO($dsn, $config['config']['database.username'], $config['config']['database.password']);
        });

    }

}