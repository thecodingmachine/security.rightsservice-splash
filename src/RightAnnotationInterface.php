<?php

namespace Mouf\Security;

use Mouf\Security\RightsService\RightsServiceInterface;

/**
 * Annotations implementing this interface can be used to check rights.
 */
interface RightAnnotationInterface
{
    /**
     * @param RightsServiceInterface $rightsService
     *
     * @return bool
     */
    public function isAllowed(RightsServiceInterface $rightsService) : bool;
}
