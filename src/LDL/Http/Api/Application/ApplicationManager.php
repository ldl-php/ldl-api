<?php

namespace LDL\Http\Api\Application;

use LDL\Http\Core\Request\RequestInterface;
use LDL\Type\Collection\Types\Object\ObjectCollection;
use LDL\Type\Exception\TypeMismatchException;

class ApplicationManager extends ObjectCollection
{

    public function validateItem($item): void
    {
        parent::validateItem($item);

        if($item instanceof ApplicationInterface){
            return;
        }

        $msg = sprintf(
          '"%s" only accepts instances of type: "%s"',
          __CLASS__,
          ApplicationInterface::class
        );

        throw new TypeMismatchException($msg);
    }

    /**
     * Matches an application through an HTTP request
     *
     * @param RequestInterface $request
     * @return ApplicationInterface
     */
    public function matchHTTPRequest(RequestInterface $request) : ?ApplicationInterface
    {
        /**
         * @var ApplicationInterface $application
         */
        foreach($this as $application){
            if(false === $application->matchHTTPRequest($request)){
                continue;
            }

            return $application;
        }

        return null;
    }

    /**
     * @return ApplicationInterface|null
     * @throws \LDL\Type\Collection\Exception\UndefinedOffsetException
     */
    public function getDefaultApplication() : ?ApplicationInterface
    {
        if(1 === count($this)){
            return $this->offsetGet(0);
        }
        /**
         * @var ApplicationInterface $application
         */
        foreach($this as $application){
           if($application->isDefault()){
               return $application;
           }
        }

        return null;
    }

}