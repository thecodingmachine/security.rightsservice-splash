<?php

namespace Mouf\Security\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ForbiddenController
{
    /**
     * Return a forbidden HTML 403 page.
     *
     * @return ResponseInterface
     */
    public function forbiddenPage(RequestInterface $requestInterface) : ResponseInterface;
}
