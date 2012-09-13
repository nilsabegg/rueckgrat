<?php

namespace Rueckgrat;

use Rueckgrat\View\View as View;

class DependencyInjectionContainer extends \Pimple
{

    public function __construct($config)
    {

        $newConfig = array();
        foreach ($config as $configSection => $configParameters) {
            $prefix = $configSection;
            foreach ($configParameters as $configOption => $configParameter) {
                $newConfig[$prefix . '.' . $configOption] = $configParameter;
            }
        }
        $this['config'] = $newConfig;
        $this['view.rootPath'] = 'index/index';
        $this['view'] =  function($config) {
            return new View($config['view.rootPath'], $config['config']);
        };
        $pimple = $this;
        $this['entityManager'] = $this->share(function($config) {
            $database = new Database($config['config']);
            $entityManager = $database->getEntityManager();
            return $entityManager;
        });

    }

}
