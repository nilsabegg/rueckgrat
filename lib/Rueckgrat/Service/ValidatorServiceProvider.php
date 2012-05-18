<?php

/**
 * Validator %Service Provider
 * 
 * This class provides a Validator class for different 
 * input types as a service.
 * 
 * @author  Alexander Feil
 * @author  Nils Abegg
 * @version 0.1
 * @package Service
 */
namespace Rueckgrat\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Rueckgrat\Service\Validator\ValidatorService;

class ValidatorServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the service
     *
     * @param  Application $app
     * @return Validator 
     */
    public function register(Application $app) 
    {
        $app['validator'] = function () {
            return new ValidatorService();	
        };
    }
}
