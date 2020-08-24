<?php

namespace LDL\Http\Api\Authentication;

use LDL\Http\Core\Request\RequestInterface;

interface AuthenticationInterface
{
    /**
     * @param RequestInterface $request
     * @return AuthenticationInterface
     *
     * @throws AuthenticationException
     */
    public function authenticate(RequestInterface $request) : AuthenticationInterface;
}