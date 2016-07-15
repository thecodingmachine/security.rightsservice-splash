<?php

namespace Mouf\Security;

use Interop\Container\ContainerInterface;
use Mouf\Security\RightsService\RightsServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * The @AndRight filter can be used to check that a user has several rights at once.
 *
 * It can only be used inside a @Right annotation or in another @AndRight or @OrRight.
 *
 * Here is a sample use case:
 *
 *  @Right(@AndRight({@Right("CAN_DO_THIS"), @Right("CAN_DO_THAT")}))
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 * @Attributes({
 *   @Attribute("value",  type = "array<Mouf\Security\RightAnnotationInterface>", required = true),
 * })
 */
class AndRight implements RightAnnotationInterface
{
    /**
     * @var RightAnnotationInterface[]
     */
    protected $rights;


    /**
     * Logged constructor.
     *
     * @param array $values
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $values)
    {
        $this->rights = $values['value'];
    }

    /**
     * @param RightsServiceInterface $rightsService
     *
     * @return bool
     */
    public function isAllowed(RightsServiceInterface $rightsService) : bool
    {
        foreach ($this->rights as $right) {
            if (!$right->isAllowed($rightsService)) {
                return false;
            }
        }
        return true;
    }
}
