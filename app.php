<?php

/**
 * Initializing
 */
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/silex/silex/src/Silex/Application.php';
//require_once '/vendors/doctrine2-orm/lib/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';


$app = new Silex\Application();


/**
 * Register namespaces
 */
$app['autoloader']->registerNamespace('Rueckgrat', __DIR__.'/lib');
$app['autoloader']->registerNamespace('Comesback', __DIR__.'/src');
$app['autoloader']->registerNamespace('Nutwerk',__DIR__.'/vendor/nutwerk/doctrine-orm-provider/lib');

/**
 * Settings
 */
$config = parse_ini_file('config.ini', true);
$app['debug'] = $config['general']['debug'];
$app['debug'] = true;
/**
 * Register Services
 */
$app->register(new Rueckgrat\Service\ViewServiceProvider(), array(
    'view.root_path' => $config['view']['root_path']
));
$app->register(new Rueckgrat\Service\ValidatorServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => $config['database']['host'],
        'dbname'    => $config['database']['dbname'],
        'user'      => $config['database']['user'],
        'password'  => $config['database']['password']
    ),
    'db.dbal.class_path'   => __DIR__.'/vendor/doctrine/orm/lib/vendor/doctrine-dbal/lib',
    'db.common.class_path' => __DIR__.'/vendor/doctrine/orm/lib/vendor/doctrine-common/lib'
));

$app->register(new Nutwerk\Provider\DoctrineORMServiceProvider(), array(
    'db.orm.class_path'            => __DIR__ . '/vendor/doctrine/orm/lib',
    'db.orm.proxies_dir'           => __DIR__ . '/cache/proxy',
    'db.orm.proxies_namespace'     => 'Proxy',
    'db.orm.cache'                 => $app['debug'] || !function_exists('apc_store')
                                          ? new \Doctrine\Common\Cache\ArrayCache()
                                          : new Doctrine\Common\Cache\ApcCache(),
    'db.orm.auto_generate_proxies' => true,
    'db.orm.entities'              => array(array(
        'type'      => 'annotation',       // entity definition 
        'path'      => __DIR__ . '/src/Comesback/Model/Entity',   // path to your entity classes
        'namespace' => 'Comesback\Model\Entity', // your classes namespace
    )),
));

/**
 * Mount Controller and run the application
 */
$cachePath = '/cache/rueckgrat/';
$fileName = 'controllerNames.csv';
if (file_exists($filename) == true && $app['debug'] == false)
{
    $controllerFileNames = str_getcsv($cachePath . $fileName);
    foreach ($controllerFileNames as $controllerFileName)
    {
       $controllerName = str_replace('Controller.php', '', $controllerFileName);
       $controllerClassName = '\\Comesback\\Controller\\' . $controllerName . 'Controller';
    }
}
else
{
    
}
$app->mount('/' . $controllerName, new $controllerClassName());
$app->run();