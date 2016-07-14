<?php
/*
 * Copyright (c) 2014 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

namespace Mouf\Security;

use Mouf\Installer\PackageInstallerInterface;
use Mouf\MoufManager;
use Mouf\Actions\InstallUtils;
use Mouf\Html\Renderer\RendererUtils;

/**
 * The installer for Moufpress.
 */
class ForbiddenMiddlewareInstaller implements PackageInstallerInterface
{
    /**
     * (non-PHPdoc).
     *
     * @see \Mouf\Installer\PackageInstallerInterface::install()
     */
    public static function install(MoufManager $moufManager)
    {
        RendererUtils::createPackageRenderer($moufManager, 'mouf/security.simplelogincontroller');

        $moufManager = MoufManager::getMoufManager();

        // These instances are expected to exist when the installer is run.
        $userService = $moufManager->getInstanceDescriptor('userService');
        $simpleLoginController = $moufManager->getInstanceDescriptor('simpleLoginController');

        // Let's create the instances.
        $ForbiddenMiddleware = InstallUtils::getOrCreateInstance('Mouf\\Security\\ForbiddenMiddleware', 'Mouf\\Security\\ForbiddenMiddleware', $moufManager);

        // Let's bind instances together.
        if (!$ForbiddenMiddleware->getConstructorArgumentProperty('userService')->isValueSet()) {
            $ForbiddenMiddleware->getConstructorArgumentProperty('userService')->setValue($userService);
        }
        if (!$ForbiddenMiddleware->getConstructorArgumentProperty('loginController')->isValueSet()) {
            $ForbiddenMiddleware->getConstructorArgumentProperty('loginController')->setValue($simpleLoginController);
        }

        // Let's rewrite the MoufComponents.php file to save the component
        $moufManager->rewriteMouf();
    }
}
