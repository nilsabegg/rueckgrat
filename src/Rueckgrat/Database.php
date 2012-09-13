<?php

namespace Rueckgrat;

use Doctrine\ORM\EntityManager as EntityManager;
use Doctrine\ORM\Configuration as DoctrineConfiguration;

class Database
{

    /**
     *
     * @var Object
     */
    protected $cache = null;

    /**
    * config
    *
    * Holds the configuration values in an array.
    *
    * @var mixed
    */
    protected $config = null;

    /**
     *
     * @var Doctrine\ORM\Configuration
     */
    protected $doctrineConfig = null;

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager = null;

    /**
     * pimple
     *
     *
     *
     * @var \Pimple
     */
    protected $pimple = null;

    /**
     * __construct
     *
     *
     *
     * @access public
     * @param  \Pimple $pimple
     * @return void
     */
    public function __construct(\Pimple $pimple)
    {

        $this->pimple = $pimple;
        $this->config = $this->pimple['config'];
        $this->doctrineConfig = new DoctrineConfiguration();
        if ($this->config['general.development'] == true) {
            $this->cache = new \Doctrine\Common\Cache\ArrayCache;
        } else {
            $this->cache = new \Doctrine\Common\Cache\ApcCache;
        }

    }

    public function getEntityManager()
    {

        if ($this->entityManager != null) {
            return $this->entityManager;
        } else {
            // throw exception
        }

    }

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

    protected function setup()
    {

        $this->setupCaches();
        $this->setupProxies();
        $entityManager = $this->createEntityManager();
        $this->registerEntityManager($entityManager);

    }

    protected function setupCaches()
    {

        $this->doctrineConfig->setMetadataCacheImpl($this->cache);
        $entityDir = $this->config['general.namespace'] . '/Model/Entity';
        $entityPath = __DIR__ . $entityDir;
        $driverImpl = $this->doctrineConfig->newDefaultAnnotationDriver($entityPath);
        $this->doctrineConfig->setMetadataDriverImpl($driverImpl);
        $this->doctrineConfig->setQueryCacheImpl($this->cache);

    }

    protected function setupProxies()
    {

        $proxyDir = $this->config['general.namespace'] . '/Model/Proxy';
        $proxyPath = __DIR__ . $proxyDir;
        $this->doctrineConfig->setProxyDir($proxyPath);
        $this->doctrineConfig->setProxyNamespace($this->config['general.namespace'] . '\Model\Proxy');
        if ($this->config['general.development'] == true) {
            $this->doctrineConfig->setAutoGenerateProxyClasses(true);
        } else {
            $this->doctrineConfig->setAutoGenerateProxyClasses(false);
        }

    }

}
