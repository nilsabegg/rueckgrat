<?php

/**
 * This file is part of the Rückgrat Framework
 */

namespace Rueckgrat;

use Doctrine\ORM\EntityManager as EntityManager;
use Doctrine\ORM\Configuration as DoctrineConfiguration;

/**
 * Database
 *
 * This class configures Doctrine and creates
 * the Doctrine entity manager.
 *
 * @author  Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Database
 */
class Database
{

    protected $annotationReader = null;

    /**
     * cache
     *
     * This class holds the Doctrine cache.
     *
     * @access protected
     * @var Object
     */
    protected $cache = null;

    protected $cachedAnnotationReader = null;

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
     * doctrineConfig
     *
     * Holds the Doctrine configuration values in
     * an array.
     *
     * @access protected
     * @var \Doctrine\ORM\Configuration
     */
    protected $doctrineConfig = null;

    /**
     * entityManager
     *
     * Holds the Doctrine entity manager.
     *
     * @access protected
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;
    protected $eventManager = null;
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
     * __construct
     *
     * Constructs the object.
     *
     * @access public
     * @param  mixed $config
     * @return void
     */
    public function __construct($config)
    {

        $this->config = $config;
        $this->doctrineConfig = new DoctrineConfiguration();
        if ($this->config['general.development'] == true) {
            $this->cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $this->cache = new \Doctrine\Common\Cache\ApcCache;
        }
        $this->setup();
    }

    /**
     * getEntityManager
     *
     * Gets the Doctrine entity manager.
     *
     * @access protected
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {

        return $this->entityManager;
        if ($this->entityManager != null) {
            return $this->entityManager;
        } else {
            // throw exception
        }

    }

    /**
     * createEntityManager
     *
     * Creates the Doctrine entity manager.
     *
     * @access protected
     * @return void
     */
    protected function createEntityManager()
    {

        $connectionOptions = array(
            'driver'   => 'pdo_mysql',
            'host'     => $this->config['database.host'],
            'port'     => $this->config['database.port'],
            'user'     => $this->config['database.username'],
            'password' => $this->config['database.password'],
            'dbname'   => $this->config['database.database'],
            'charset' => 'utf8',
            'driverOptions' => array(
                    1002=>'SET NAMES utf8'
            )
        );
        $this->entityManager = EntityManager::create($connectionOptions, $this->doctrineConfig, $this->eventManager);

    }

    /**
     * setup
     *
     * Setup Doctrine
     *
     * @access protected
     * @return void
     */
    protected function setup()
    {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
            __DIR__.'/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
        );
        $this->createCaches();
        $this->createProxies();
        $this->createAnnotationReaders();
        $this->createDriver();
        $this->createEventManager();
        $this->createEntityManager();

    }
    protected function createAnnotationReaders()
    {

        $this->annotationReader = new \Doctrine\Common\Annotations\AnnotationReader();
        $this->cachedAnnotationReader = new \Doctrine\Common\Annotations\CachedReader(
            $this->annotationReader,
            $this->cache
        );

    }
    /**
     * createCaches
     *
     * Creates the caches for Doctrine.
     *
     * @access protected
     * @return void
     */
    protected function createCaches()
    {

        $this->doctrineConfig->setMetadataCacheImpl($this->cache);
        $this->doctrineConfig->setQueryCacheImpl($this->cache);

    }
    protected function createDriver()
    {

        $this->driverChain = new \Doctrine\ORM\Mapping\Driver\DriverChain();
        \Gedmo\DoctrineExtensions::registerAbstractMappingIntoDriverChainORM(
            $this->driverChain, // our metadata driver chain, to hook into
            $this->cachedAnnotationReader // our cached annotation reader
        );
        $entityDir = $this->config['general.namespace'] . '/Model/Entity';
        $entityPath = $this->config['general.appDir'] . 'src/' . $entityDir;
        $this->annotationDriver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
            $this->cachedAnnotationReader, // our cached annotation reader
           array($entityPath) // paths to look in
        );
        $this->driverChain->addDriver($this->annotationDriver, $this->config['general.namespace'] . '\\Model\\Entity');
        $this->doctrineConfig->setMetadataDriverImpl($this->driverChain);

    }

    protected function createEventManager()
    {

        $this->eventManager = new \Doctrine\Common\EventManager();
        if ($this->config['general.i18n'] == true) {
            $this->registerI18n();
        }
        $this->eventManager->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit());
    }

    /**
     * createProxies
     *
     * Creates the proxies for Doctrine
     *
     * @access protected
     * @return void
     */
    protected function createProxies()
    {

        $proxyDir = $this->config['general.namespace'] . '/Model/Proxy';
        $proxyPath = $this->config['general.appDir'] . 'src/' . $proxyDir;
        $this->doctrineConfig->setProxyDir($proxyPath);
        $this->doctrineConfig->setProxyNamespace($this->config['general.namespace'] . '\Model\Proxy');
        if ($this->config['general.development'] == true) {
            $this->doctrineConfig->setAutoGenerateProxyClasses(true);
        } else {
            $this->doctrineConfig->setAutoGenerateProxyClasses(false);
        }

    }

    protected function registerI18n()
    {

        $translatableListener = new \Gedmo\Translatable\TranslatableListener();
        $translatableListener->setTranslatableLocale('en');
        $translatableListener->setDefaultLocale($this->config['general.default_language']);
        $translatableListener->setAnnotationReader($this->cachedAnnotationReader);
        $this->eventManager->addEventSubscriber($translatableListener);

    }
}
