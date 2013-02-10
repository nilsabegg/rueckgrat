<?php

/**
 * Autoloader
 *
 * SplClassLoader implementation that implements the technical interoperability
 * standards for PHP 5.3 namespaces and class names.
 *
 * http://groups.google.com/group/php-standards/web/final-proposal
 *
 *     // Example which loads classes for the Doctrine Common package in the
 *     // Doctrine\Common namespace.
 *     $classLoader = new SplClassLoader('Doctrine\Common', '/path/to/doctrine');
 *     $classLoader->register();
 *
 * @author Jonathan H. Wage <jonwage@gmail.com>
 * @author Roman S. Borschel <roman@code-factory.org>
 * @author Matthew Weier O'Phinney <matthew@zend.com>
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 * @author Fabien Potencier <fabien.potencier@symfony-project.org>
 * @author Nils Abegg <rueckgrat@nilsabegg.de>
 * @version 0.1
 * @package Rueckgrat
 * @category Class Loading
 */
class Autoloader
{

    /**
     * fileExtension
     *
     * Holds the extension of the file.
     *
     * @access protected
     * @var string
     */
    protected $fileExtension = '.php';

    /**
     * fileExtension
     *
     * Holds the path to autoload.
     *
     * @access protected
     * @var string
     */
    protected $includePath;

    /**
     * namespace
     *
     * Holds the namespace to autoload.
     *
     * @access protected
     * @var string
     */
    protected $namespace;

    /**
     * namespaceSeparator
     *
     * Holds the namespace separator.
     *
     * @access protected
     * @var string
     */
    protected $namespaceSeparator = '\\';

    /**
     * __construct
     *
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     *
     * @access public
     * @param string $namespace
     * @param string $includePath
     * @return void
     */
    public function __construct($namespace = null, $includePath = null)
    {

        $this->namespace = $namespace;
        $this->includePath = $includePath;

    }

    /**
     * setNamespaceSeparator
     *
     * Sets the namespace separator used by classes in the
     * namespace of this class loader.
     *
     * @access public
     * @param string $separator
     * @return void
     */
    public function setNamespaceSeparator($separator)
    {

        $this->namespaceSeparator = $separator;

    }

    /**
     * getNamespaceSeparator
     *
     * Gets the namespace seperator used by classes in the namespace
     * of this class loader.
     *
     * @access public
     * @return void
     */
    public function getNamespaceSeparator()
    {

        return $this->namespaceSeparator;

    }

    /**
     * setIncludePath
     *
     * Sets the base include path for all class files in the namespace
     * of this class loader.
     *
     * @access public
     * @param string $includePath
     * @return void
     */
    public function setIncludePath($includePath)
    {

        $this->includePath = $includePath;

    }

    /**
     * getIncludePath
     *
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @access public
     * @return void
     */
    public function getIncludePath()
    {

        return $this->includePath;

    }

    /**
     * setFileExtension
     *
     * Sets the file extension of class files in the namespace of this class loader.
     *
     * @access public
     * @param string $fileExtension
     * @return void
     */
    public function setFileExtension($fileExtension)
    {

        $this->fileExtension = $fileExtension;

    }

    /**
     * getFilexExtension
     *
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @access public
     * @return string $fileExtension
     */
    public function getFileExtension()
    {

        return $this->fileExtension;

    }

    /**
     * register
     *
     * Installs this class loader on the SPL autoload stack.
     *
     * @access public
     * @return void
     */
    public function register()
    {

        spl_autoload_register(array($this, 'loadClass'));

    }

    /**
     * unregister
     *
     * Uninstalls this class loader from the SPL autoloader stack.
     *
     * @access public
     * @return void
     */
    public function unregister()
    {

        spl_autoload_unregister(array($this, 'loadClass'));

    }

    /**
     * loadClass
     *
     * Loads the given class or interface.
     *
     * @access public
     * @param  string $className The name of the class to load.
     * @return void
     */
    public function loadClass($className)
    {
        $namespace1 = $this->namespace . $this->namespaceSeparator;
        $namespace2 = substr($className, 0, strlen($this->namespace . $this->namespaceSeparator));
        if (null === $this->namespace || $namespace1 === $namespace2) {
            $fileName = '';
            $namespace = '';
            if (false !== ($lastNsPos = strripos($className, $this->namespaceSeparator))) {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $namespacePath = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $namespace);
                $fileName = $namespacePath . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . $this->fileExtension;

            require ($this->includePath !== null ? $this->includePath . DIRECTORY_SEPARATOR : '') . $fileName;
        }

    }

}

