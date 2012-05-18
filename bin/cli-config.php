<?php

require_once __DIR__ . '/../vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';
require_once __DIR__ . '/../vendor/autoload.php';
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', realpath(__DIR__ . '/../vendor/doctrine/orm/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', realpath(__DIR__ . '/../vendor/doctrine/dbal/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', realpath(__DIR__ . '/../vendor/doctrine/common/lib'));
$classLoader->register();
//$classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(__DIR__ . '/../vendor/doctrine/orm/lib/vendor'));
//$classLoader->register();
//$classLoader = new \Doctrine\Common\ClassLoader('Rueckgrat', __DIR__ . '/../..');
//$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Comesback\Model\Entity', realpath(__DIR__ . '/../src/Comesback/Model/Entity'));
$classLoader->register();
//$classLoader = new \Doctrine\Common\ClassLoader('Rueckgrat\App\Model\Proxy', __DIR__ . '/../app/model/proxy');
//$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
Doctrine\Common\Annotations\AnnotationRegistry::registerFile(__DIR__ . '/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
$reader = new Doctrine\Common\Annotations\AnnotationReader();
$driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, array(__DIR__ . "/../src/Comesback/Model/Entity"));
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir(__DIR__ . '/../cache/proxy');
$config->setProxyNamespace('Proxy');

$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname'    => 'testing',
    'user'      => 'root',
    'password'  => 'root',
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);