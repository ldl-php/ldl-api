<?php

namespace LDL\Http\Api\Application;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\Types\Generic\GenericFileType;
use LDL\FS\Util\Path;
use LDL\Http\Core\Request\RequestInterface;
use LDL\Http\Router\Route\Config\Parser\RouteConfigParserCollection;
use LDL\Http\Router\Route\Factory\RouteFactory;
use LDL\Http\Router\Route\Group\RouteGroup;
use LDL\Http\Router\Router;
use LDL\Http\Router\Schema\SchemaRepository;
use LDL\Http\Router\Schema\SchemaRepositoryInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var SchemaRepository
     */
    private $schemaRepository;

    /**
     * @var string
     */
    private $parentClassDirectory;

    /**
     * @var RouteConfigParserCollection
     */
    private $configParsers;

    /**
     * @var bool
     */
    private $routerInitialized=false;

    /**
     * @var bool
     */
    private $isDefault;

    public function __construct(
        string $name,
        string $prefix,
        Router $router,
        bool $isDefault = false,
        SchemaRepository $schemaRepo=null,
        RouteConfigParserCollection $configParsers = null,
        bool $active = true
    )
    {
        $this->router = $router;
        $this->prefix = $prefix;
        $this->name = $name;
        $this->active = $active;
        $this->schemaRepository = $schemaRepo;
        $this->configParsers = $configParsers;
        $this->isDefault = $isDefault;

        $rc = new \ReflectionClass(get_class($this));
        $this->parentClassDirectory = dirname($rc->getFileName());
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getPrefix() : string
    {
        return $this->prefix;
    }

    public function getSchemaRepository(): ?SchemaRepositoryInterface
    {
        if($this->schemaRepository){
            return $this->schemaRepository;
        }

        $schemaFiles = LocalFileFinder::findRegex(
            '.*\.json',
            [
                Path::make(
                    $this->parentClassDirectory,
                    'Schema'
                )
            ]
        );

        $schemaRepo = new SchemaRepository();

        /**
         * @var GenericFileType $schemaFile
         */
        foreach($schemaFiles as $schemaFile){
            $file = $schemaFile->getFileName();
            $file = substr($file, 0, strrpos($file, '.'));

            $schemaRepo->append($schemaFile->getRealPath(), "$file.schema");
        }

        return $this->schemaRepository = $schemaRepo;
    }

    public function matchHTTPRequest(RequestInterface $request): ?ApplicationInterface
    {
        if($request->getHeaderBag()->get('X-API-Application') === $this->name){
            return $this;
        }

        if($request->get('x-api-application') === $this->name){
            return $this;
        }

        return null;
    }

    public function getRouter(ContainerInterface $container=null): Router
    {
        if($this->routerInitialized){
            return $this->router;
        }

        $routeFiles = LocalFileFinder::findRegex(
            '.*-endpoint\.json',
            [
                Path::make(
                    $this->parentClassDirectory,
                    'Endpoint'
                )
            ]
        );

        foreach($routeFiles as $routeFile){
            $routes = RouteFactory::fromJsonFile(
                $routeFile,
                $container,
                $this->getSchemaRepository(),
                $this->configParsers ?? new RouteConfigParserCollection()
            );

            $group = new RouteGroup($this->name, $this->prefix, $routes);
            $this->router->addGroup($group);
        }

        return $this->router;
    }
}