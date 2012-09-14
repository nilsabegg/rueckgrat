<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

use Rueckgrat\View\View as View;

/**
 * DependencyInjectionContainer
 *
 * This class handles the templates of
 * the application.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Dependency Injection
 */
class DependencyInjectionContainer extends \Pimple
{

    /**
     * __construct
     *
     * Constructs the object.
     *
     * @param mixed  $config
     * @return void
     */
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
        $this['entityManager'] = $this->share(function($config) {
            $database = new Database($config['config']);
            $entityManager = $database->getEntityManager();
            return $entityManager;
        });

    }

}
