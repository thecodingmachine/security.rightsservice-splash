<?php

namespace Mouf\Security;

use Interop\Container\ContainerInterface;
use Mouf\Security\Controllers\ForbiddenController;
use Mouf\Security\Controllers\LoginController;
use Mouf\Security\RightsService\RightInterface;
use Mouf\Security\RightsService\RightsServiceInterface;
use Mouf\Security\UserService\UserServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ForbiddenMiddleware implements ForbiddenMiddlewareInterface
{
    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @var RightsServiceInterface
     */
    private $rightsService;

    /**
     * @var LoginController
     */
    private $loginController;

    /**
     * @var ForbiddenController
     */
    private $forbiddenController;

    /**
     * @var RightAnnotationInterface
     */
    private $right;

    /**
     * @param RightsServiceInterface    $rightsService
     * @param ForbiddenController       $forbiddenController
     * @param UserServiceInterface|null $userService
     * @param LoginController|null      $loginController
     */
    public function __construct(RightsServiceInterface $rightsService, ForbiddenController $forbiddenController, UserServiceInterface $userService = null, LoginController $loginController = null)
    {
        $this->rightsService = $rightsService;
        $this->loginController = $loginController;
        $this->userService = $userService;
        $this->forbiddenController = $forbiddenController;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     * @param ContainerInterface     $container
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler, RightAnnotationInterface $rightAnnotation) : ResponseInterface
    {

        // Do we have the right?
        if (!$rightAnnotation->isAllowed($this->rightsService)) {
            // If we are not logged, try a 401, otherwise, let's go 403.
            $isLogged = ($this->userService !== null) ? $this->userService->isLogged() : true;
            if (!$isLogged && $this->loginController !== null) {
                return $this->loginController->loginPage($request, $request->getUri());
            } else {
                return $this->forbiddenController->forbiddenPage($request);
            }
        }

        return $handler->handle($request);
    }

}
