<?php

namespace LDL\Http\Api\Service\Application\Application\Config;

class ApplicationConfig implements ApplicationConfigInterface
{
    public const DEFAULT_PREFIX_NAME = 'SampleApplication';
    /**
     * @var string
     */
    private $name = self::DEFAULT_PREFIX_NAME;

    /**
     * @var string
     */
    private $prefix = self::DEFAULT_PREFIX_NAME;

    /**
     * @var string
     */
    private $source;

    /**
     * @var string
     */
    private $dispatcherFolderName = 'Dispatcher';

    /**
     * @var string
     */
    private $endpointFolderName = 'Endpoint';

    /**
     * @var string
     */
    private $schemaFolderName = 'Schema';

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $instance->setSource(sprintf(
            '%s%s%s',
            getcwd(),
            DIRECTORY_SEPARATOR,
            $instance->getName()
        ));
        $defaults = $instance->toArray();
        $merge = array_replace_recursive($defaults, $options);

        return $instance->setName($merge['name'])
            ->setPrefix($merge['prefix'])
            ->setSource($merge['source'])
            ->setDispatcherFolderName($merge['dispatcher']['folder'])
            ->setEndpointFolderName($merge['endpoint']['folder'])
            ->setSchemaFolderName($merge['schema']['folder']);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'prefix' => $this->getPrefix(),
            'source' => $this->getSource(),
            'dispatcher' => [
                'folder' => $this->getDispatcherFolderName()
            ],
            'endpoint' => [
                'folder' => $this->getEndpointFolderName()
            ],
            'schema' => [
                'folder' => $this->getSchemaFolderName()
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ApplicationConfig
     */
    private function setName(string $name): ApplicationConfigInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return ApplicationConfig
     */
    private function setPrefix(string $prefix): ApplicationConfigInterface
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return ApplicationConfig
     */
    private function setSource(string $source): ApplicationConfigInterface
    {
        $this->source = $source;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDispatcherFolderName(): string
    {
        return $this->dispatcherFolderName;
    }

    /**
     * @param string $dispatcherFolderName
     * @return ApplicationConfig
     */
    private function setDispatcherFolderName(string $dispatcherFolderName): ApplicationConfig
    {
        $this->dispatcherFolderName = $dispatcherFolderName;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpointFolderName(): string
    {
        return $this->endpointFolderName;
    }

    /**
     * @param string $endpointFolderName
     * @return ApplicationConfig
     */
    private function setEndpointFolderName(string $endpointFolderName): ApplicationConfig
    {
        $this->endpointFolderName = $endpointFolderName;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaFolderName(): string
    {
        return $this->schemaFolderName;
    }

    /**
     * @param string $schemaFolderName
     * @return ApplicationConfig
     */
    private function setSchemaFolderName(string $schemaFolderName): ApplicationConfig
    {
        $this->schemaFolderName = $schemaFolderName;
        return $this;
    }
}