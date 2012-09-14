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

    /**
     * cache
     *
     * This class holds the Doctrine cache.
     *
     * @access protected
     * @var Object
     */
    protected $cache = null;

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
     *
     * @access protected
     * @var \Doctrine\ORM\Configuration
     */
    protected $doctrineConfig = null;

    /**
     * entityManager
     *
     *
     * @access protected
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;

    /**
     * pimple
     *
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
     * @param  \Pimple $pimple
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
            'dbname'   => $this->config['database.database']
        );
        $this->entityManager = EntityManager::create($connectionOptions, $this->doctrineConfig);

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

        $this->createCaches();
        $this->createProxies();
        $this->createEntityManager();

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
        $entityDir = $this->config['general.namespace'] . '/Model/Entity';
        $entityPath = $this->config['general.appDir'] . 'src/' . $entityDir;
        $driverImpl = $this->doctrineConfig->newDefaultAnnotationDriver($entityPath);
        $this->doctrineConfig->setMetadataDriverImpl($driverImpl);
        $this->doctrineConfig->setQueryCacheImpl($this->cache);

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

}
