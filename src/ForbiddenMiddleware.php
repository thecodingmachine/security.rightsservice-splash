<?php

namespace Mouf\Security;

use Interop\Container\ContainerInterface;
use Mouf\Security\Controllers\ForbiddenController;
use Mouf\Security\Controllers\LoginController;
use Mouf\Security\RightsService\RightsServiceInterface;
use Mouf\Security\UserService\UserServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ForbiddenMiddleware
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next, ContainerInterface $container, RightAnnotationInterface $right)
    {

        // Do we have the right?
        if (!$right->isAllowed($this->rightsService)) {
            // If we are not logged, try a 401, otherwise, let's go 403.
            $isLogged = ($this->userService !== null) ? $this->userService->isLogged() : true;
            if (!$isLogged && $this->loginController !== null) {
                $response = $this->loginController->loginPage($request);
                if($response->getStatusCode() === 200) {
                    $response = $response->withStatus(401);
                }
                return $response;
            } else {
                $response = $this->forbiddenController->forbiddenPage($request);
                if($response->getStatusCode() === 200) {
                    $response = $response->withStatus(403);
                }
                return $response;
            }
        }

        $response = $next($request, $response, $next);

        return $response;
    }
}
