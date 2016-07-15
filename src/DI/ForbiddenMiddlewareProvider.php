<?php

namespace Mouf\Security\DI;

use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use Mouf\Security\Controllers\ForbiddenController;
use Mouf\Security\Controllers\LoginController;
use Mouf\Security\ForbiddenMiddleware;
use Mouf\Security\RightsService\RightsServiceInterface;
use Mouf\Security\UserService\UserServiceInterface;

class ForbiddenMiddlewareProvider implements ServiceProvider
{
    /**
     * Returns a list of all container entries registered by this service provider.
     *
     * - the key is the entry name
     * - the value is a callable that will return the entry, aka the **factory**
     *
     * Factories have the following signature:
     *        function(ContainerInterface $container, callable $getPrevious = null)
     *
     * About factories parameters:
     *
     * - the container (instance of `Interop\Container\ContainerInterface`)
     * - a callable that returns the previous entry if overriding a previous entry, or `null` if not
     *
     * @return callable[]
     */
    public function getServices()
    {
        return [
            ForbiddenMiddleware::class => function (ContainerInterface $container) {
                $userService = $container->has(UserServiceInterface::class) ? $container->get(UserServiceInterface::class) : null;
                $loginController = $container->has(LoginController::class) ? $container->get(LoginController::class) : null;

                return new ForbiddenMiddleware($container->get(RightsServiceInterface::class), $container->get(ForbiddenController::class), $userService, $loginController);
            },
        ];
    }
}
