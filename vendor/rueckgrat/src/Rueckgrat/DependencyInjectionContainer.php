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
        $this['view'] =  function ($c) {
            return new View($c['view.rootPath'], $c['config']);
        };

        $this['databaseHandler'] = $this->share(function ($c) {
            $dsn = 'mysql:host=' . $c['config']['database.host'] . ';dbname=' . $c['config']['database.database'];
            return new \PDO($dsn, $c['config']['database.username'], $c['config']['database.password']);
        });

    }

}