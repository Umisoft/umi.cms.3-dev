<?php

namespace umitest;

use AspectMock\Kernel;
use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Go\Core\GoAspectContainer;
use Go\Instrument\ClassLoading\SourceTransformingLoader;
use Go\Instrument\Transformer\FilterInjectorTransformer;
use Symfony\Component\Finder\Finder;


/**
 * {@inheritdoc}
 */
class AopKernel extends Kernel
{
    /**
     * {@inheritdoc}
     * Full copy parent logic, bs cannot override static dependency AopComposerLoader
     */
    public function init(array $options = array())
    {
        if (!isset($options['excludePaths'])) {
            $options['excludePaths'] = [];
        }
        if (!isset($options['debug'])) {
            $options['debug'] = true;
        }
        $options['excludePaths'][] = __DIR__;

        $this->options = $this->normalizeOptions($options);
        define('AOP_CACHE_DIR', $this->options['cacheDir']);

        /** @var $container GoAspectContainer */
        $container = $this->container = new $this->options['containerClass'];
        $container->set('kernel', $this);
        $container->set('kernel.interceptFunctions', $this->options['interceptFunctions']);
        $container->set('kernel.options', $this->options);

        SourceTransformingLoader::register();

        foreach ($this->registerTransformers() as $sourceTransformer) {
            SourceTransformingLoader::addTransformer($sourceTransformer);
        }

        // Register kernel resources in the container for debug mode
        if ($this->options['debug']) {
            $this->addKernelResourcesToContainer($container);
        }

        $allowedNamespaces = array();
        if (isset($this->options['allowedNamespaces'])) {
            $allowedNamespaces = $this->options['allowedNamespaces'];
        }

        AopComposerLoader::init($allowedNamespaces);

        // Register all AOP configuration in the container
        $this->configureAop($container);
    }
}

/**
 * AopComposerLoader class is responsible to use a weaver for classes instead of original one
 * @package Go\Instrument\ClassLoading
 */
class AopComposerLoader
{

    /**
     * Instance of original autoloader
     * @var ClassLoader
     */
    protected $original = null;

    public $allowedNamespaces = [
        'umicms\form\element'
    ];

    /**
     * Constructs an wrapper for the composer loader
     * @param ClassLoader $original Instance of current loader
     */
    public function __construct(ClassLoader $original, $allowedNamespaces = array())
    {
        $this->original = $original;
        $this->allowedNamespaces = $allowedNamespaces;
    }

    /**
     * Initialize aspect autoloader
     * Replaces original composer autoloader with wrapper
     */
    public static function init(array $allowedNamespaces = [])
    {
        $loaders = spl_autoload_functions();

        foreach ($loaders as &$loader) {
            $loaderToUnregister = $loader;
            if (is_array($loader) && ($loader[0] instanceof ClassLoader)) {
                /**
                 * @var ClassLoader $originalLoader
                 */
                $originalLoader = $loader[0];

                // Configure library loader for doctrine annotation loader
                AnnotationRegistry::registerLoader(
                    function ($class) use ($originalLoader) {
                        $originalLoader->loadClass($class);

                        return class_exists($class, false);
                    }
                );
                $loader[0] = new AopComposerLoader($loader[0], $allowedNamespaces);
            }
            spl_autoload_unregister($loaderToUnregister);
        }
        unset($loader);

        foreach ($loaders as $loader) {
            spl_autoload_register($loader);
        }
    }

    /**
     * Autoload a class by it's name
     */
    public function loadClass($class)
    {
        if ($file = $this->original->findFile($class)) {
            $isInternal = true;
            foreach ($this->allowedNamespaces as $ns) {
                if (strpos($class, $ns) === 0) {
                    $isInternal = false;
                    break;
                }
            }

            include($isInternal ? $file : FilterInjectorTransformer::rewrite($file));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findFile($class)
    {
        return $this->original->findFile($class);
    }
}


