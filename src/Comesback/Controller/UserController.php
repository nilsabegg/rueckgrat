<?php

/**
 * %Rueckgrat %Controller 
 * 
 * This class manages the main bootstrapping and calls
 * the right sub controller
 * 
 * - Silex Documentation: http://silex.sensiolabs.org/doc/providers.html#controllers-providers
 * - Symfony Route API:   http://api.symfony.com/2.0/Symfony/Component/Routing/Route.html
 *
 * @author  Alexander Feil
 * @author  Nils Abegg
 * @version 1.0
 * @package Controller
 */
namespace Comesback\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Rueckgrat\Base\ControllerProvider;

class UserController extends ControllerProvider implements ControllerProviderInterface
{
    /**
     * Connect controller to route
     * 
     * @param  Application          $app
     * @return ControllerCollection 
     */
    public function connect(Application $app)
    {
        $controllers = new ControllerCollection();
        $controllers->get('/user/create', function() use($app) {
                        $article = new \Comesback\Model\Entity\Action();
                        $article->setName('Hello world!');
                        $app['db.orm.em']->persist($article);
                        $app['db.orm.em']->flush();
                        $view = $app['view'];
                        $view->set('id1',1);
                        $view->set('id2',2);
			return $view->render('project');
                });
        $controllers->get('/project/settings', function() use($app) {
                        $em = $app['doctrine.orm.em'];

			return $view->render('project');
                });

        return $controllers;
    }

}