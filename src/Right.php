<?php

namespace Mouf\Security;

use Mouf\Security\RightsService\RightsServiceInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheCodingMachine\Splash\Filters\FilterInterface;

/**
 * The @Right filter should be used to check whether a user has
 * a certain right or not.
 *
 * It will try to do so by querying the "rightsService" instance, that should
 * be an instance of the "MoufRightService" class (or a class extending the RightsServiceInterface).
 *
 * This filter requires at least one parameter: "name"
 *
 * So @Right(name="Admin") will require the Admin right to be logged.
 * Otherwise, the user is redirected to an error page.
 *
 * You can pass an additional parameter to overide the name of the instance.
 * For instance: @Right(name="Admin",middlewareName="myRightMiddleware") will
 * verify that the user has the correct right using the "myRightService" instance.
 *
 *
 * @Annotation
 * @Target({"METHOD","CLASS","ANNOTATION"})
 * @Attributes({
 *   @Attribute("name", type = "string"),
 *   @Attribute("right", type = "Mouf\Security\RightAnnotationInterface"),
 *   @Attribute("middlewareName", type = "string"),
 * })
 */
class Right implements FilterInterface, RightAnnotationInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * The value passed to the filter.
     */
    protected $middlewareName;

    /**
     * Logged constructor.
     *
     * @param array $values
     *
     * @throws \BadMethodCallException
     */
    public function __construct(array $values)
    {
        $this->middlewareName = $values['middlewareName'] ?? ForbiddenMiddleware::class;
        if (!isset($values['value']) && !isset($values['name']) && !isset($values['right'])) {
            throw new \BadMethodCallException('The @Right annotation must be passed a right name or another right annotation. For instance: "@Right(\'my_right\')"');
        }
        $this->name = $values['value'] ?? $values['name'] ?? $values['right'];
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler, ContainerInterface $container): ResponseInterface
    {
        /** @var ForbiddenMiddlewareInterface $middleware */
        $middleware = $container->get($this->middlewareName);
        return $middleware->process($request, $handler, $this);
    }

    /**
     * @param RightsServiceInterface $rightsService
     *
     * @return bool
     */
    public function isAllowed(RightsServiceInterface $rightsService) : bool
    {
        if ($this->name instanceof RightAnnotationInterface) {
            return $this->name->isAllowed($rightsService);
        } else {
            return $rightsService->isAllowed($this->name);
        }
    }
}
