<?php

namespace LDL\Http\Api\Service\Application\Application\Writer\Options;

class ApplicationWriterOptions
{
    /**
     * @var string
     */
    private $file = 'application-config.json';

    /**
     * @var bool
     */
    private $force = false;

    /**
     * @var string
     */
    private $filePerms = '0666';

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = $instance->toArray();
        $merge = array_merge($defaults, $options);

        return $instance->setFile($merge['file'])
            ->setFilePerms($merge['filePerms'])
            ->setForce($merge['force']);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return ApplicationWriterOptions
     */
    public function setFile(string $file): ApplicationWriterOptions
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }

    /**
     * @param bool $force
     * @return ApplicationWriterOptions
     */
    private function setForce(bool $force): ApplicationWriterOptions
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePerms(): string
    {
        return $this->filePerms;
    }

    /**
     * @param string $filePerms
     * @return ApplicationWriterOptions
     */
    private function setFilePerms(string $filePerms): ApplicationWriterOptions
    {
        $this->filePerms = $filePerms;
        return $this;
    }
}