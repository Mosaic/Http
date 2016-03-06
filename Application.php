<?php

namespace Mosaic;

use Interop\Container\Definition\DefinitionProviderInterface;
use Mosaic\Contracts\Application as ApplicationContract;
use Mosaic\Contracts\Container\Container;
use Mosaic\Contracts\Container\ContainerDefinition;
use Mosaic\Contracts\Exceptions\ExceptionRunner;
use Mosaic\Contracts\Http\Server;
use Mosaic\Definitions\LaravelContainerDefinition;
use Mosaic\Foundation\Bootstrap\HandleExceptions;
use Mosaic\Foundation\Bootstrap\LoadConfiguration;
use Mosaic\Foundation\Bootstrap\LoadEnvironmentVariables;
use Mosaic\Foundation\Bootstrap\LoadRoutes;
use Mosaic\Foundation\Bootstrap\RegisterDefinitions;
use Mosaic\Foundation\Components\Registry;

class Application implements ApplicationContract
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Server
     */
    protected $context;

    /**
     * @var null
     */
    protected $path;

    /**
     * @var string
     */
    protected $env = 'production';

    /**
     * @var array
     */
    protected $bootstrappers = [
        RegisterDefinitions::class,
        LoadEnvironmentVariables::class,
        LoadConfiguration::class,
        HandleExceptions::class,
        LoadRoutes::class,
    ];

    /**
     * Application constructor.
     *
     * @param string $path
     * @param string $containerDefinition
     */
    public function __construct(string $path, string $containerDefinition = LaravelContainerDefinition::class)
    {
        $this->path     = $path;
        $this->registry = new Registry();

        $this->defineContainer(new $containerDefinition);
    }

    /**
     * Application root path
     *
     * @param string $path
     *
     * @return string
     */
    public function path(string $path = '') : string
    {
        return rtrim($this->path . '/' . $path, '/');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function configPath(string $path = '') : string
    {
        return $this->path('config/' . $path);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function storagePath(string $path = '') : string
    {
        return $this->path('storage/' . $path);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function viewsPath(string $path = '') : string
    {
        return $this->path('resources/views/' . $path);
    }

    /**
     * @param DefinitionProviderInterface $definition
     */
    public function define(DefinitionProviderInterface $definition)
    {
        $this->getRegistry()->define($definition, $this);
    }

    /**
     * @param string[] $definitions
     */
    public function definitions(array $definitions)
    {
        foreach ($definitions as $definition) {
            $this->define(
                $this->getContainer()->make($definition)
            );
        }
    }

    /**
     * @return Registry
     */
    public function getRegistry() : Registry
    {
        return $this->registry;
    }

    /**
     * @return Container
     */
    public function getContainer() : Container
    {
        return $this->container;
    }

    /**
     * Define a container implementation
     *
     * @param ContainerDefinition $definition
     *
     * @return Container
     */
    public function defineContainer(ContainerDefinition $definition) : Container
    {
        $this->container = $definition->getDefinition();
        $this->container->instance(Container::class, $this->container);
        $this->container->instance(ApplicationContract::class, $this);

        return $this->container;
    }

    /**
     * Bootstrap the Application
     */
    public function bootstrap()
    {
        foreach ($this->bootstrappers as $bootstrapper) {
            $this->getContainer()->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * @return string
     */
    public function env() : string
    {
        return $this->env;
    }

    /**
     * @param string $env
     */
    public function setEnvironment(string $env)
    {
        $this->env = $env;
    }

    /**
     * @return bool
     */
    public function isLocal() : bool
    {
        return $this->env() === 'local';
    }

    /**
     * @param ExceptionRunner $runner
     */
    public function setExceptionRunner(ExceptionRunner $runner)
    {
        $this->getContainer()->bind(ExceptionRunner::class, function () use ($runner) {
            return $runner;
        });
    }

    /**
     * @return ExceptionRunner
     */
    public function getExceptionRunner() : ExceptionRunner
    {
        return $this->getContainer()->make(ExceptionRunner::class);
    }

    /**
     * @param string $context
     */
    public function setContext(string $context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext() : string
    {
        return $this->context;
    }
}
