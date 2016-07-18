<?php

namespace Mouf\Security;

use Mouf\Security\RightsService\RightsServiceInterface;

/**
 * The @OrRight filter can be used to check that a user has one right amongst a list of required rights.
 *
 * It can only be used inside a @Right annotation or in another @AndRight or @OrRight.
 *
 * Here is a sample use case:
 *
 *  @Right(@OrRight({@Right("CAN_DO_THIS"), @Right("CAN_DO_THAT")}))
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 * @Attributes({
 *   @Attribute("value",  type = "array<Mouf\Security\RightAnnotationInterface>", required = true),
 * })
 */
class OrRight implements RightAnnotationInterface
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
            if ($right->isAllowed($rightsService)) {
                return true;
            }
        }

        return false;
    }
}
