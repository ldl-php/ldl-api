<?php

namespace LDL\Http\Api\Service\Application\Application\Reader;

use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfig;
use LDL\Http\Api\Service\Application\Application\Config\ApplicationConfigInterface;

class ApplicationReaderService implements ApplicationReaderServiceInterface
{
    public static function read(string $file): ApplicationConfigInterface
    {
        if(!is_readable($file)){
            $msg = sprintf(
                'Could not read file "%s", file is not readable',
                $file
            );

            throw new Exception\ApplicationReaderPermissionException($msg);
        }

        try {
            $config = json_decode(
                \file_get_contents($file),
                true,
                512,
                \JSON_THROW_ON_ERROR
            );
        }catch(\Exception $e){
            $msg = "Failed to decode file contents";
            throw new Exception\ApplicationReaderDecodeException($msg);
        }

        return ApplicationConfig::fromArray($config);
    }
}