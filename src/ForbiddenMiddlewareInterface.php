<?php
namespace Mouf\Security;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

interface ForbiddenMiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler, RightAnnotationInterface $rightAnnotation) : ResponseInterface;

}
