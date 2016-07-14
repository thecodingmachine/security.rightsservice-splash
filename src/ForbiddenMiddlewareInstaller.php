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
        RendererUtils::createPackageRenderer($moufManager, 'mouf/security.rightsservice-splash');

        // These instances are expected to exist when the installer is run.
        $rightsService = $moufManager->getInstanceDescriptor('rightsService');
        $userService = $moufManager->getInstanceDescriptor('userService');
        $simpleLoginController = $moufManager->getInstanceDescriptor('simpleLoginController');
        $bootstrapTemplate = $moufManager->getInstanceDescriptor('bootstrapTemplate');
        $block_content = $moufManager->getInstanceDescriptor('block.content');

        // Let's create the instances.
        $Mouf_Security_ForbiddenMiddleware = InstallUtils::getOrCreateInstance('Mouf\\Security\\ForbiddenMiddleware', 'Mouf\\Security\\ForbiddenMiddleware', $moufManager);
        $anonymousSimpleForbiddenController = $moufManager->createInstance('Mouf\\Security\\Controllers\\SimpleForbiddenController');
        $anonymousSimpleForbiddenView = $moufManager->createInstance('Mouf\\Security\\Html\\SimpleForbiddenView');

        // Let's bind instances together.
        if (!$Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('rightsService')->isValueSet()) {
            $Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('rightsService')->setValue($rightsService);
        }
        if (!$Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('forbiddenController')->isValueSet()) {
            $Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('forbiddenController')->setValue($anonymousSimpleForbiddenController);
        }
        if (!$Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('userService')->isValueSet()) {
            $Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('userService')->setValue($userService);
        }
        if (!$Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('loginController')->isValueSet()) {
            $Mouf_Security_ForbiddenMiddleware->getConstructorArgumentProperty('loginController')->setValue($simpleLoginController);
        }
        $anonymousSimpleForbiddenController->getConstructorArgumentProperty('template')->setValue($bootstrapTemplate);
        $anonymousSimpleForbiddenController->getConstructorArgumentProperty('contentBlock')->setValue($block_content);
        $anonymousSimpleForbiddenController->getConstructorArgumentProperty('simpleForbiddenView')->setValue($anonymousSimpleForbiddenView);

        // Let's rewrite the MoufComponents.php file to save the component
        $moufManager->rewriteMouf();
    }
}
